# Nizaam - Developer Guide

## Getting Started as a Developer

Welcome to the Nizaam codebase! This guide will help you understand how to work with the system.

---

## Understanding the Request Flow

```
1. User visits URL → .htaccess rewrites to public/index.php
2. index.php loads core files and routes
3. Router matches URL to controller action
4. Middleware checks authentication/authorization
5. Controller receives request
6. Controller calls Service/Model as needed
7. Service executes business logic
8. Model interacts with database
9. Controller returns View or JSON
10. Response sent to user
```

---

## Adding a New Feature

### Example: Add a "Announcements" Feature

#### Step 1: Create Database Table

Edit `database/schema.sql` and add:
```sql
CREATE TABLE announcements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    published_by INT UNSIGNED NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (published_by) REFERENCES employees(id)
) ENGINE=InnoDB;
```

#### Step 2: Create Model

Create `app/Models/Announcement.php`:
```php
<?php

class Announcement extends Model
{
    protected $table = 'announcements';

    public function getActive()
    {
        return $this->findAll(['is_active' => 1], 'created_at DESC', 10);
    }

    public function getWithPublisher($id)
    {
        $sql = "SELECT a.*, e.full_name as publisher_name
                FROM {$this->table} a
                INNER JOIN employees e ON a.published_by = e.id
                WHERE a.id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
}
```

#### Step 3: Create Controller

Create `app/Controllers/AnnouncementController.php`:
```php
<?php

class AnnouncementController extends Controller
{
    private $announcementModel;
    private $auditLog;

    public function __construct()
    {
        parent::__construct();
        $this->announcementModel = new Announcement();
        $this->auditLog = new AuditLog();
    }

    public function index()
    {
        $announcements = $this->announcementModel->getActive();
        $this->view('announcements.index', ['announcements' => $announcements]);
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            return;
        }
        $this->view('announcements.create');
    }

    public function store()
    {
        if (!$this->isAdmin() || !Request::isPost()) {
            http_response_code(403);
            return;
        }

        $employee = $this->getCurrentEmployee();
        
        $id = $this->announcementModel->create([
            'title' => Request::input('title'),
            'content' => Request::input('content'),
            'published_by' => $employee['id'],
            'is_active' => true
        ]);

        $this->auditLog->log(
            $this->getCurrentUser()['id'],
            'create',
            'announcement',
            $id
        );

        Session::flash('success', 'Announcement published');
        $this->redirect('/announcements');
    }
}
```

#### Step 4: Create Views

Create `app/Views/announcements/index.php`:
```php
<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Announcements</h2>
    <?php if ($this->isAdmin()): ?>
    <a href="<?= $this->getBaseUrl() ?>/announcements/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> New Announcement
    </a>
    <?php endif; ?>
</div>

<div class="row">
    <?php foreach ($announcements as $announcement): ?>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($announcement['title']) ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($announcement['content'])) ?></p>
                <small class="text-muted">
                    Posted <?= date('M d, Y', strtotime($announcement['created_at'])) ?>
                </small>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
$title = 'Announcements';
require __DIR__ . '/../layout.php';
?>
```

#### Step 5: Add Routes

Edit `app/routes.php` and add:
```php
$router->get('/announcements', [AnnouncementController::class, 'index'], [AuthMiddleware::class]);
$router->get('/announcements/create', [AnnouncementController::class, 'create'], [AdminMiddleware::class]);
$router->post('/announcements/create', [AnnouncementController::class, 'store'], [AdminMiddleware::class]);
```

#### Step 6: Update Autoloader

Edit `public/index.php` and add:
```php
require_once __DIR__ . '/../app/Models/Announcement.php';
require_once __DIR__ . '/../app/Controllers/AnnouncementController.php';
```

#### Step 7: Add to Navigation

Edit `app/Views/layout.php` and add to sidebar:
```php
<li class="nav-item">
    <a class="nav-link" href="<?= $this->getBaseUrl() ?>/announcements">
        <i class="bi bi-megaphone"></i> Announcements
    </a>
</li>
```

#### Step 8: Run Migration

Run in MySQL:
```sql
USE nizaam;
-- Add the CREATE TABLE statement here
```

Done! Your feature is live.

---

## Working with Workflows

### Adding a New Work Type

Let's add "Performance Review" as a work type.

#### Step 1: Update Database Enum

```sql
ALTER TABLE work_items 
MODIFY COLUMN type ENUM('task', 'leave_request', 'expense', 'timesheet', 'performance_review') NOT NULL;

ALTER TABLE workflows 
MODIFY COLUMN work_type ENUM('task', 'leave_request', 'expense', 'timesheet', 'performance_review') NOT NULL;
```

#### Step 2: Create Workflow

```sql
INSERT INTO workflows (name, work_type, description, is_active) 
VALUES ('Performance Review Workflow', 'performance_review', 'Annual performance review process', TRUE);

-- Get the workflow_id from the above insert
SET @workflow_id = LAST_INSERT_ID();

-- Add statuses
INSERT INTO workflow_statuses (workflow_id, status_name, status_order, is_initial, is_final, color) VALUES
(@workflow_id, 'Draft', 1, TRUE, FALSE, '#6B7280'),
(@workflow_id, 'Self Assessment', 2, FALSE, FALSE, '#3B82F6'),
(@workflow_id, 'Manager Review', 3, FALSE, FALSE, '#F59E0B'),
(@workflow_id, 'HR Review', 4, FALSE, FALSE, '#8B5CF6'),
(@workflow_id, 'Completed', 5, FALSE, TRUE, '#10B981');

-- Add transitions (get status IDs first)
INSERT INTO workflow_transitions (workflow_id, from_status_id, to_status_id, requires_approval, approver_role)
SELECT @workflow_id, 
       (SELECT id FROM workflow_statuses WHERE workflow_id = @workflow_id AND status_name = 'Draft'),
       (SELECT id FROM workflow_statuses WHERE workflow_id = @workflow_id AND status_name = 'Self Assessment'),
       FALSE, NULL;
```

#### Step 3: Create Service

Create `app/Services/PerformanceReviewService.php`:
```php
<?php

class PerformanceReviewService
{
    private $workflowService;

    public function __construct()
    {
        $this->workflowService = new WorkflowService();
    }

    public function createReview($employeeId, $reviewPeriod, $reviewerId)
    {
        $metadata = [
            'review_period' => $reviewPeriod,
            'reviewer_id' => $reviewerId,
            'goals' => [],
            'ratings' => []
        ];

        return $this->workflowService->createWorkItem(
            'performance_review',
            "Performance Review: $reviewPeriod",
            "Annual performance review for review period: $reviewPeriod",
            $reviewerId,
            $employeeId,
            'medium',
            null,
            null,
            $metadata
        );
    }
}
```

---

## Common Tasks

### Adding a New Field to Employee

1. Add column to database:
```sql
ALTER TABLE employees ADD COLUMN phone_number VARCHAR(20) NULL AFTER email;
```

2. Update Employee model (it will automatically work with base Model CRUD)

3. Update views to display/edit the new field

### Customizing Email Notifications

Create `app/Services/EmailService.php`:
```php
<?php

class EmailService
{
    public function send($to, $subject, $body)
    {
        // Use PHPMailer or mail() function
        $headers = 'From: noreply@nizaam.com' . "\r\n" .
                   'Content-Type: text/html; charset=UTF-8' . "\r\n";
        
        return mail($to, $subject, $body, $headers);
    }

    public function sendWorkItemAssignment($workItem, $employee)
    {
        $subject = "New Work Item Assigned: {$workItem['title']}";
        $body = "You have been assigned a new work item...";
        
        return $this->send($employee['email'], $subject, $body);
    }
}
```

### Adding a Report

Create method in `ReportController`:
```php
public function leaveReport()
{
    $sql = "SELECT e.full_name, e.department, 
                   lt.name as leave_type,
                   SUM(lb.used) as total_used
            FROM employees e
            INNER JOIN leave_balances lb ON e.id = lb.employee_id
            INNER JOIN leave_types lt ON lb.leave_type_id = lt.id
            WHERE lb.year = YEAR(CURDATE())
            GROUP BY e.id, lt.id
            ORDER BY e.full_name";
    
    $data = $this->db->fetchAll($sql);
    
    $this->view('reports.leave', ['data' => $data]);
}
```

---

## Debugging Tips

### Enable Error Display

In `public/index.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Database Query Debugging

In any model:
```php
try {
    $result = $this->db->fetchAll($sql, $params);
    // Debug: print the query
    // var_dump($sql, $params); exit;
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
}
```

### Session Debugging

```php
var_dump($_SESSION);
var_dump(Session::get('user'));
```

### Audit Log Debugging

Every action is logged. Check the audit_log table:
```sql
SELECT * FROM audit_log 
WHERE entity_type = 'work_item' 
ORDER BY created_at DESC 
LIMIT 20;
```

---

## Code Style Guide

### Controller Example
```php
public function actionName()
{
    // 1. Validate access
    if (!$this->isAdmin()) {
        http_response_code(403);
        return;
    }

    // 2. Validate input
    $validation = Request::validate([
        'field' => 'required|min:3'
    ]);

    if ($validation !== true) {
        Session::flash('errors', $validation);
        $this->redirect('/back');
        return;
    }

    // 3. Process business logic
    try {
        $result = $this->service->doSomething();
        
        // 4. Audit log
        $this->auditLog->log($userId, 'action', 'entity', $entityId);
        
        // 5. Success response
        Session::flash('success', 'Action completed');
        $this->redirect('/success');
    } catch (Exception $e) {
        // 6. Error handling
        Session::flash('error', $e->getMessage());
        $this->redirect('/error');
    }
}
```

### Model Example
```php
public function getWithRelation($id)
{
    $sql = "SELECT t1.*, t2.name as related_name
            FROM {$this->table} t1
            LEFT JOIN related_table t2 ON t1.related_id = t2.id
            WHERE t1.id = ?";
    return $this->db->fetchOne($sql, [$id]);
}
```

### Service Example
```php
public function complexOperation($data)
{
    $this->db->beginTransaction();
    
    try {
        // Multiple operations
        $id = $this->model->create($data);
        $this->relatedModel->update($relatedId, $updates);
        
        // Send notification
        $this->notificationModel->createNotification(...);
        
        $this->db->commit();
        return $id;
    } catch (Exception $e) {
        $this->db->rollback();
        throw $e;
    }
}
```

---

## Testing Checklist

Before committing changes:

- [ ] Test all CRUD operations
- [ ] Test with different user roles (admin/user)
- [ ] Verify CSRF tokens work
- [ ] Check mobile responsiveness
- [ ] Verify audit logging works
- [ ] Test error scenarios
- [ ] Check for SQL injection vulnerabilities
- [ ] Validate input properly
- [ ] Test workflow transitions
- [ ] Verify notifications are sent

---

## Git Workflow (If Using Git)

```bash
# Create feature branch
git checkout -b feature/announcements

# Make changes and commit
git add .
git commit -m "Add announcements feature"

# Push and create PR
git push origin feature/announcements
```

---

## Performance Optimization Tips

1. **Add Database Indexes**:
```sql
CREATE INDEX idx_employee_status ON employees(employment_status);
CREATE INDEX idx_workitem_assigned ON work_items(assigned_to);
```

2. **Use Eager Loading**: Load related data in one query instead of multiple

3. **Cache Frequently Used Data**: Store in session or use Redis

4. **Optimize Images**: Compress uploaded files

5. **Use EXPLAIN**: Analyze slow queries
```sql
EXPLAIN SELECT * FROM work_items WHERE assigned_to = 5;
```

---

## Useful SQL Queries for Development

### Find All Work Items for an Employee
```sql
SELECT wi.*, ws.status_name, e.full_name as assignee
FROM work_items wi
INNER JOIN workflow_statuses ws ON wi.current_status_id = ws.id
LEFT JOIN employees e ON wi.assigned_to = e.id
WHERE wi.created_by = 1 OR wi.assigned_to = 1;
```

### Leave Balance Report
```sql
SELECT e.full_name, lt.name, lb.quota, lb.used, lb.remaining
FROM leave_balances lb
INNER JOIN employees e ON lb.employee_id = e.id
INNER JOIN leave_types lt ON lb.leave_type_id = lt.id
WHERE lb.year = YEAR(CURDATE());
```

### Audit Trail for an Entity
```sql
SELECT al.*, u.email as actor
FROM audit_log al
LEFT JOIN users u ON al.actor_id = u.id
WHERE al.entity_type = 'employee' AND al.entity_id = 5
ORDER BY al.created_at DESC;
```

---

## Need Help?

- Review the architecture documentation: `ARCHITECTURE.md`
- Check code comments in existing files
- Review similar features for patterns
- Test changes in development before deploying

---

**Happy Coding! 🚀**
