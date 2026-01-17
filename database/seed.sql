-- Seed data for Nizaam

USE nizaam;

-- Insert default leave types
INSERT INTO leave_types (name, code, description, is_paid, requires_approval) VALUES
('Annual Leave', 'ANNUAL', 'Paid annual vacation leave', TRUE, TRUE),
('Sick Leave', 'SICK', 'Paid sick leave', TRUE, TRUE),
('Unpaid Leave', 'UNPAID', 'Unpaid leave for personal reasons', FALSE, TRUE),
('Emergency Leave', 'EMERGENCY', 'Emergency personal leave', TRUE, TRUE);

-- Insert default leave quotas by designation
INSERT INTO leave_quotas (designation, leave_type_id, annual_quota) VALUES
('Manager', 1, 25),
('Manager', 2, 15),
('Senior Developer', 1, 22),
('Senior Developer', 2, 15),
('Developer', 1, 20),
('Developer', 2, 12),
('HR Specialist', 1, 22),
('HR Specialist', 2, 15),
('Accountant', 1, 20),
('Accountant', 2, 12);

-- Create default workflows
INSERT INTO workflows (name, work_type, description, is_active) VALUES
('Standard Task Workflow', 'task', 'Default workflow for tasks', TRUE),
('Leave Request Workflow', 'leave_request', 'Approval workflow for leave requests', TRUE),
('Expense Reimbursement Workflow', 'expense', 'Approval workflow for expenses', TRUE),
('Timesheet Workflow', 'timesheet', 'Weekly timesheet approval workflow', TRUE);

-- Task workflow statuses
INSERT INTO workflow_statuses (workflow_id, status_name, status_order, is_initial, is_final, color) VALUES
(1, 'Open', 1, TRUE, FALSE, '#3B82F6'),
(1, 'In Progress', 2, FALSE, FALSE, '#F59E0B'),
(1, 'Review', 3, FALSE, FALSE, '#8B5CF6'),
(1, 'Done', 4, FALSE, TRUE, '#10B981'),
(1, 'Cancelled', 5, FALSE, TRUE, '#6B7280');

-- Leave request workflow statuses
INSERT INTO workflow_statuses (workflow_id, status_name, status_order, is_initial, is_final, color) VALUES
(2, 'Submitted', 1, TRUE, FALSE, '#3B82F6'),
(2, 'Manager Review', 2, FALSE, FALSE, '#F59E0B'),
(2, 'HR Review', 3, FALSE, FALSE, '#8B5CF6'),
(2, 'Approved', 4, FALSE, TRUE, '#10B981'),
(2, 'Rejected', 5, FALSE, TRUE, '#EF4444');

-- Expense workflow statuses
INSERT INTO workflow_statuses (workflow_id, status_name, status_order, is_initial, is_final, color) VALUES
(3, 'Submitted', 1, TRUE, FALSE, '#3B82F6'),
(3, 'Manager Review', 2, FALSE, FALSE, '#F59E0B'),
(3, 'Finance Review', 3, FALSE, FALSE, '#8B5CF6'),
(3, 'Approved', 4, FALSE, TRUE, '#10B981'),
(3, 'Rejected', 5, FALSE, TRUE, '#EF4444');

-- Timesheet workflow statuses
INSERT INTO workflow_statuses (workflow_id, status_name, status_order, is_initial, is_final, color) VALUES
(4, 'Draft', 1, TRUE, FALSE, '#6B7280'),
(4, 'Submitted', 2, FALSE, FALSE, '#3B82F6'),
(4, 'Manager Review', 3, FALSE, FALSE, '#F59E0B'),
(4, 'Approved', 4, FALSE, TRUE, '#10B981'),
(4, 'Rejected', 5, FALSE, TRUE, '#EF4444');

-- Task workflow transitions
INSERT INTO workflow_transitions (workflow_id, from_status_id, to_status_id, requires_approval, approver_role) VALUES
(1, 1, 2, FALSE, NULL),
(1, 2, 3, FALSE, NULL),
(1, 3, 4, FALSE, NULL),
(1, 1, 5, FALSE, NULL),
(1, 2, 5, FALSE, NULL);

-- Leave workflow transitions
INSERT INTO workflow_transitions (workflow_id, from_status_id, to_status_id, requires_approval, approver_role) VALUES
(2, 6, 7, TRUE, 'manager'),
(2, 7, 8, TRUE, 'hr'),
(2, 8, 9, FALSE, NULL),
(2, 7, 10, TRUE, 'manager'),
(2, 8, 10, TRUE, 'hr');

-- Expense workflow transitions
INSERT INTO workflow_transitions (workflow_id, from_status_id, to_status_id, requires_approval, approver_role) VALUES
(3, 11, 12, TRUE, 'manager'),
(3, 12, 13, TRUE, 'finance'),
(3, 13, 14, FALSE, NULL),
(3, 12, 15, TRUE, 'manager'),
(3, 13, 15, TRUE, 'finance');

-- Timesheet workflow transitions
INSERT INTO workflow_transitions (workflow_id, from_status_id, to_status_id, requires_approval, approver_role) VALUES
(4, 16, 17, FALSE, NULL),
(4, 17, 18, TRUE, 'manager'),
(4, 18, 19, FALSE, NULL),
(4, 18, 20, TRUE, 'manager');

-- Create admin user (password: admin123)
INSERT INTO users (email, password_hash, role, is_active) VALUES
('admin@nizaam.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', TRUE);

-- Create admin employee profile
INSERT INTO employees (user_id, employee_id, full_name, designation, department, employment_status, join_date) VALUES
(1, 'EMP001', 'System Administrator', 'System Admin', 'IT', 'active', '2026-01-01');
