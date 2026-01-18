# Nizaam - Complete File Structure

## 📁 Project Overview

This document lists all files in the Nizaam project and their purposes.

---

## Root Directory

```
nizaam/
├── .htaccess                 # URL rewriting to public/
├── README.md                 # Main documentation
├── SETUP.md                  # Quick setup guide
├── ARCHITECTURE.md           # System architecture details
├── DEVELOPER.md              # Developer guide
├── install.bat              # Windows installation script
```

---

## `/public/` - Web Root

**Purpose**: Entry point for all HTTP requests

```
public/
├── index.php                # Application bootstrap
└── .htaccess               # Apache URL rewriting
```

**Key Files**:
- `index.php`: Loads core files, routes requests, handles errors

---

## `/config/` - Configuration

**Purpose**: Application and database configuration

```
config/
├── app.php                 # Application settings (timezone, uploads, etc.)
└── database.php            # Database connection credentials
```

**Key Files**:
- `app.php`: App name, URL, timezone, upload limits
- `database.php`: MySQL host, database name, credentials

---

## `/database/` - Database Scripts

**Purpose**: Database schema and seed data

```
database/
├── schema.sql              # Complete database schema
└── seed.sql                # Initial data (users, workflows, leave types)
```

**Key Tables Created**:
- users, employees, work_items, workflows, workflow_statuses
- workflow_transitions, projects, project_members
- leave_types, leave_quotas, leave_balances
- notifications, audit_log, sessions
- attachments, comments, work_item_history

---

## `/app/` - Application Core

### Core Framework Files

```
app/
├── Database.php            # Singleton PDO database wrapper
├── Router.php              # URL routing with middleware support
├── Controller.php          # Base controller class
├── Model.php               # Base model class with CRUD
├── Session.php             # Session management & CSRF
├── Request.php             # HTTP request handling & validation
└── routes.php              # Route definitions
```

**Core Classes**:
- `Database`: PDO connection, query methods, transactions
- `Router`: Route registration, URL matching, middleware execution
- `Controller`: View rendering, redirects, JSON responses, auth helpers
- `Model`: CRUD operations, query builder, relationships
- `Session`: Start/destroy, flash messages, CSRF tokens
- `Request`: Input retrieval, validation, file uploads

---

### `/app/Middleware/` - Request Filters

**Purpose**: Filter requests before reaching controllers

```
app/Middleware/
├── AuthMiddleware.php      # Require authenticated user
├── AdminMiddleware.php     # Require admin role
└── GuestMiddleware.php     # Redirect authenticated users
```

**Usage**: Attached to routes for access control

---

### `/app/Models/` - Data Access Layer

**Purpose**: Database entity management

```
app/Models/
├── User.php                # Authentication, password management
├── Employee.php            # Employee CRUD, relationships
├── WorkItem.php            # Work items with details, history
├── Workflow.php            # Workflow configuration, transitions
├── Project.php             # Projects, members
├── Notification.php        # Notifications, read/unread
└── AuditLog.php            # Audit logging (append-only)
```

**Key Methods**:
- `User`: authenticate(), createUser(), updatePassword()
- `Employee`: getAllWithUsers(), getManager(), getDirectReports()
- `WorkItem`: getWithDetails(), getHistory(), updateStatus()
- `Workflow`: findByType(), getStatuses(), canTransition()
- `Project`: getMembers(), addMember(), removeMember()
- `Notification`: getForEmployee(), markAsRead()
- `AuditLog`: log(), getForEntity(), search()

---

### `/app/Services/` - Business Logic Layer

**Purpose**: Complex business operations

```
app/Services/
├── WorkflowService.php     # Work item lifecycle, transitions, notifications
└── LeaveService.php        # Leave balance, quotas, approval
```

**Key Methods**:
- `WorkflowService`:
  - createWorkItem(): Create work item with initial status
  - transitionWorkItem(): Change status with validation
  - getAvailableTransitions(): Get allowed next statuses
  
- `LeaveService`:
  - getLeaveBalance(): Get employee leave balance
  - initializeLeaveBalance(): Set up annual quotas
  - submitLeaveRequest(): Create leave request work item
  - approveLeaveRequest(): Deduct from balance

---

### `/app/Controllers/` - Request Handlers

**Purpose**: Handle HTTP requests and responses

```
app/Controllers/
├── AuthController.php          # Login, logout
├── DashboardController.php     # Main dashboard
├── EmployeeController.php      # Employee CRUD
├── WorkItemController.php      # Work item management
├── ProjectController.php       # Project management
├── LeaveController.php         # Leave management
├── NotificationController.php  # Notifications
├── ReportController.php        # Admin reports
├── AuditController.php         # Audit log viewer
├── ExpenseController.php       # Expense submissions
└── TimesheetController.php     # Timesheet submissions
```

**Controller Actions**:
- `index()`: List view
- `show($id)`: Detail view
- `create()`: Show create form
- `store()`: Process create
- `edit($id)`: Show edit form
- `update($id)`: Process update
- `delete($id)`: Process delete

---

### `/app/Views/` - UI Templates

**Purpose**: HTML templates for rendering

```
app/Views/
├── layout.php                  # Master layout (sidebar, topbar, footer)
├── auth/
│   └── login.php              # Login page
├── dashboard/
│   └── index.php              # Main dashboard
├── employees/
│   ├── index.php              # Employee list
│   ├── show.php               # Employee profile
│   ├── create.php             # Add employee form
│   └── edit.php               # Edit employee form
├── work_items/
│   ├── index.php              # Work item list
│   ├── show.php               # Work item details
│   └── create.php             # Create work item form
├── leaves/
│   ├── index.php              # Leave balances & requests
│   └── create.php             # Leave request form
├── projects/
│   ├── index.php              # Project list
│   ├── show.php               # Project details
│   └── create.php             # Create project form
├── expenses/
│   └── create.php             # Expense submission form
├── timesheets/
│   └── create.php             # Timesheet submission form
├── notifications/
│   └── index.php              # Notification list
├── reports/
│   └── index.php              # Reports dashboard
└── audit/
    └── index.php              # Audit log viewer
```

**View Pattern**:
```php
<?php ob_start(); ?>
<!-- Page content here -->
<?php
$content = ob_get_clean();
$title = 'Page Title';
require __DIR__ . '/../layout.php';
?>
```

---

## `/storage/` - File Storage

**Purpose**: Uploaded files and temporary data

```
storage/
└── uploads/                # User uploaded files
```

**Security**: Files should not be directly accessible (configure in production)

---

## Routes Map

### Guest Routes (No Auth Required)
```
GET  /login          → AuthController::showLogin()
POST /login          → AuthController::login()
```

### Authenticated Routes
```
GET  /                      → DashboardController::index()
GET  /dashboard             → DashboardController::index()
GET  /logout                → AuthController::logout()

# Employees
GET  /employees             → EmployeeController::index()
GET  /employees/create      → EmployeeController::create() [Admin]
POST /employees/create      → EmployeeController::store() [Admin]
GET  /employees/{id}        → EmployeeController::show()
GET  /employees/{id}/edit   → EmployeeController::edit()
POST /employees/{id}/edit   → EmployeeController::update()

# Work Items
GET  /work-items            → WorkItemController::index()
GET  /work-items/create     → WorkItemController::create()
POST /work-items/create     → WorkItemController::store()
GET  /work-items/{id}       → WorkItemController::show()
POST /work-items/{id}/status → WorkItemController::updateStatus()
POST /work-items/{id}/comment → WorkItemController::addComment()

# Projects
GET  /projects              → ProjectController::index()
GET  /projects/create       → ProjectController::create()
POST /projects/create       → ProjectController::store()
GET  /projects/{id}         → ProjectController::show()
POST /projects/{id}/members → ProjectController::addMember()

# Leaves
GET  /leaves                → LeaveController::index()
GET  /leaves/request        → LeaveController::create()
POST /leaves/request        → LeaveController::store()

# Notifications
GET  /notifications         → NotificationController::index()
POST /notifications/{id}/read → NotificationController::markAsRead()
POST /notifications/read-all  → NotificationController::markAllAsRead()

# Admin Only
GET  /reports               → ReportController::index() [Admin]
GET  /audit                 → AuditController::index() [Admin]

# Expenses & Timesheets
GET  /expenses/create       → ExpenseController::create()
POST /expenses/create       → ExpenseController::store()
GET  /timesheets/create     → TimesheetController::create()
POST /timesheets/create     → TimesheetController::store()
```

---

## Database Entity Relationships

```
users (1) ←→ (1) employees
                    ↓ (1)
                    ↓
                    ↓ (many)
              work_items ←→ projects
                    ↓
                    ↓
          workflow_statuses
                    ↓
              work_item_history

employees (1) ←→ (many) leave_balances
leave_types (1) ←→ (many) leave_balances

work_items (1) ←→ (many) comments
work_items (1) ←→ (many) attachments

projects (1) ←→ (many) project_members
```

---

## Security Features Implemented

✅ **Authentication**: bcrypt password hashing, session management
✅ **CSRF Protection**: Token validation on all POST requests
✅ **SQL Injection**: PDO prepared statements only
✅ **Access Control**: Role-based middleware
✅ **Audit Logging**: All actions tracked
✅ **Session Security**: Regeneration on login
✅ **Input Validation**: Server-side validation

---

## File Count Summary

- **Total Files**: 60+
- **PHP Files**: 45+
- **SQL Files**: 2
- **Documentation**: 4
- **Configuration**: 2
- **HTML/Views**: 15+

---

## Key Design Decisions

1. **No Framework**: Pure PHP for full control and learning
2. **MVC Pattern**: Clear separation of concerns
3. **Service Layer**: Business logic separated from controllers
4. **Workflow Engine**: Configurable, no hardcoded workflows
5. **Audit Trail**: Complete activity tracking for compliance
6. **Bootstrap 5**: Modern, responsive UI
7. **PDO**: Secure database access
8. **Session-based Auth**: Simple, effective authentication

---

## Next Steps for Customization

1. **Branding**: Update colors in `app/Views/layout.php`
2. **Workflows**: Add/modify in database `workflows` table
3. **Leave Types**: Customize in `leave_types` table
4. **Reports**: Add custom reports in `ReportController`
5. **Email**: Integrate SMTP in a new `EmailService`
6. **Permissions**: Extend role system in `users.role`

---

**Complete File Structure - Ready for Development!** 🎉
