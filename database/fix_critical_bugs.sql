-- Critical Bug Fixes for Nizaam Database
-- Run this SQL script to fix data integrity issues

-- 1. Fix invalid join_date for employee #98 (River Russell)
UPDATE employees 
SET join_date = '2024-02-28' 
WHERE id = 98 AND (join_date = '0000-00-00' OR join_date IS NULL);

-- 2. Initialize leave balances for all employees who don't have them
-- This is a temporary fix; the proper fix is in the code to always initialize on employee creation

-- First, create a stored procedure to initialize balances for all employees
DELIMITER $$

DROP PROCEDURE IF EXISTS initialize_missing_leave_balances$$

CREATE PROCEDURE initialize_missing_leave_balances()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE emp_id INT;
    DECLARE emp_designation VARCHAR(100);
    DECLARE current_year INT DEFAULT YEAR(CURDATE());
    DECLARE cur CURSOR FOR 
        SELECT e.id, e.designation 
        FROM employees e
        WHERE e.id NOT IN (
            SELECT DISTINCT employee_id 
            FROM leave_balances 
            WHERE year = current_year
        );
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO emp_id, emp_designation;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Insert leave balances for this employee
        -- Try to get quotas for their designation, if not found use defaults
        INSERT INTO leave_balances (employee_id, leave_type_id, year, quota, used, remaining)
        SELECT 
            emp_id,
            COALESCE(lq.leave_type_id, lt.id) as leave_type_id,
            current_year,
            COALESCE(lq.annual_quota, 15) as quota,
            0 as used,
            COALESCE(lq.annual_quota, 15) as remaining
        FROM leave_types lt
        LEFT JOIN leave_quotas lq ON lt.id = lq.leave_type_id AND lq.designation = emp_designation
        WHERE NOT EXISTS (
            SELECT 1 FROM leave_balances lb 
            WHERE lb.employee_id = emp_id 
            AND lb.leave_type_id = lt.id 
            AND lb.year = current_year
        );

    END LOOP;

    CLOSE cur;
END$$

DELIMITER ;

-- Execute the procedure to fix missing balances
CALL initialize_missing_leave_balances();

-- 3. Create additional leave quotas for designations that don't have them
-- This prevents future issues with employees not getting leave balances

INSERT IGNORE INTO leave_quotas (designation, leave_type_id, annual_quota)
SELECT DISTINCT 
    e.designation,
    lt.id as leave_type_id,
    CASE 
        WHEN lt.code = 'ANNUAL' THEN 20
        WHEN lt.code = 'SICK' THEN 12
        WHEN lt.code = 'EMERGENCY' THEN 5
        ELSE 0
    END as annual_quota
FROM employees e
CROSS JOIN leave_types lt
WHERE NOT EXISTS (
    SELECT 1 FROM leave_quotas lq 
    WHERE lq.designation = e.designation 
    AND lq.leave_type_id = lt.id
)
AND lt.is_paid = 1;

-- 4. Add index to improve performance on leave balance lookups
CREATE INDEX IF NOT EXISTS idx_leave_balances_employee_year 
ON leave_balances(employee_id, year);

CREATE INDEX IF NOT EXISTS idx_work_items_assigned_to 
ON work_items(assigned_to);

CREATE INDEX IF NOT EXISTS idx_work_items_created_by 
ON work_items(created_by);

-- 5. Verify the fixes
SELECT 'Data Integrity Check Results:' as status;

SELECT 
    'Employees with invalid join dates' as check_name,
    COUNT(*) as count
FROM employees 
WHERE join_date = '0000-00-00' OR join_date IS NULL;

SELECT 
    'Employees without leave balances for current year' as check_name,
    COUNT(DISTINCT e.id) as count
FROM employees e
WHERE e.employment_status = 'active'
AND e.id NOT IN (
    SELECT DISTINCT employee_id 
    FROM leave_balances 
    WHERE year = YEAR(CURDATE())
);

SELECT 
    'Total employees' as stat_name,
    COUNT(*) as count
FROM employees;

SELECT 
    'Total leave balances' as stat_name,
    COUNT(*) as count
FROM leave_balances
WHERE year = YEAR(CURDATE());

-- Show summary of leave balances by employee
SELECT 
    e.employee_id,
    e.full_name,
    e.designation,
    COUNT(lb.id) as leave_types_configured,
    SUM(lb.quota) as total_annual_quota,
    SUM(lb.used) as total_used,
    SUM(lb.remaining) as total_remaining
FROM employees e
LEFT JOIN leave_balances lb ON e.id = lb.employee_id AND lb.year = YEAR(CURDATE())
WHERE e.employment_status = 'active'
GROUP BY e.id, e.employee_id, e.full_name, e.designation
ORDER BY leave_types_configured ASC, e.full_name
LIMIT 20;
