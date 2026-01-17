# Nizaam - Architecture & Design Document

## System Architecture

### Overview
Nizaam follows a clean **MVC (Model-View-Controller)** architecture pattern implemented in core PHP without any frameworks. The system is designed for maintainability, security, and scalability.

### Architectural Layers

```
┌─────────────────────────────────────────┐
│           Presentation Layer            │
│    (Views - Bootstrap 5 + Custom CSS)   │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│          Application Layer              │
│  (Controllers + Middleware + Router)    │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│           Business Logic Layer          │
│       (Services + Workflow Engine)      │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│            Data Access Layer            │
│         (Models + Database PDO)         │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│            Database Layer               │
│        (MySQL - Relational DB)          │
└─────────────────────────────────────────┘
```

---

## Core Components

### 1. Router (`app/Router.php`)
- **Purpose**: Maps URLs to controller actions
- **Features**:
  - RESTful routing with dynamic parameters
  - Middleware support per route
  - GET/POST method handling
  - Clean URL structure via `.htaccess`

### 2. Controller (`app/Controller.php`)
- **Purpose**: Base class for all controllers
- **Responsibilities**:
  - Handle HTTP requests
  - Validate input
  - Call services/models
  - Return views or JSON
  - Manage redirects and sessions
- **Rule**: Controllers stay thin - no business logic!

### 3. Model (`app/Model.php`)
- **Purpose**: Base class for database entities
- **Features**:
  - CRUD operations (Create, Read, Update, Delete)
  - Query builder helpers
  - PDO prepared statements
  - Relationship management
- **Rule**: Models only handle data access, not business rules

### 4. Services (`app/Services/`)
- **Purpose**: Encapsulate business logic
- **Examples**:
  - `WorkflowService`: Manages work item lifecycle and transitions
  - `LeaveService`: Handles leave balance calculations and approvals
- **Rule**: All complex business logic lives here, not in controllers or models

### 5. Middleware (`app/Middleware/`)
- **Purpose**: Filter requests before reaching controllers
- **Types**:
  - `AuthMiddleware`: Ensures user is logged in
  - `AdminMiddleware`: Ensures user has admin role
  - `GuestMiddleware`: Redirects authenticated users away from login

### 6. Session (`app/Session.php`)
- **Purpose**: Manage user sessions securely
- **Features**:
  - Start/destroy sessions
  - Flash messages
  - CSRF token generation and validation
  - Session regeneration on login

### 7. Request (`app/Request.php`)
- **Purpose**: Handle HTTP request data
- **Features**:
  - Input retrieval with defaults
  - File uploads
  - Validation rules
  - IP and user agent detection

### 8. Database (`app/Database.php`)
- **Purpose**: Singleton PDO wrapper
- **Features**:
  - Prepared statements
  - Transaction support
  - Error handling
  - Single database connection

---

## Security Implementation

### 1. Authentication
```
User Login → Password Verify (bcrypt) → Session Create → Regenerate Session ID
```

### 2. CSRF Protection
- Token generated on session start
- Validated on all POST requests
- Stored in hidden form fields

### 3. SQL Injection Prevention
- PDO prepared statements everywhere
- No string concatenation in queries
- Parameterized queries only

### 4. Access Control
```
Request → Middleware Check → Role Validation → Allow/Deny
```

### 5. Audit Logging
- Every action logged
- Append-only table
- No updates or deletes allowed
- IP address and user agent tracked

---

## Database Design

### Core Tables

#### Users
```sql
id, email, password_hash, role, is_active, last_login
```
**Purpose**: Authentication and role management

#### Employees
```sql
id, user_id, employee_id, full_name, designation, 
department, manager_id, employment_status, join_date
```
**Purpose**: System of record for all employees

#### Work Items
```sql
id, title, description, type, created_by, assigned_to,
workflow_id, current_status_id, priority, due_date, 
project_id, metadata (JSON)
```
**Purpose**: Universal work entity for tasks, leaves, expenses, timesheets

#### Workflows
```sql
id, name, work_type, description, is_active
```
**Purpose**: Define workflow configurations

#### Workflow Statuses
```sql
id, workflow_id, status_name, status_order, 
is_initial, is_final, color
```
**Purpose**: Define available statuses per workflow

#### Workflow Transitions
```sql
id, workflow_id, from_status_id, to_status_id,
requires_approval, approver_role
```
**Purpose**: Define allowed status transitions

#### Audit Log
```sql
id, actor_id, action, entity_type, entity_id,
metadata (JSON), ip_address, user_agent, created_at
```
**Purpose**: Immutable audit trail

---

## Workflow Engine Design

### Concept
Everything flows through configurable workflows. No hardcoded business logic.

### Workflow Lifecycle

1. **Creation**: Work item created with initial status
2. **Transition**: Status changes based on allowed transitions
3. **Approval**: Optional approval required for certain transitions
4. **History**: Every change tracked in work_item_history
5. **Notifications**: Relevant parties notified automatically

### Example: Leave Request Workflow

```
Status: Submitted (Initial)
    ↓ (requires_approval: manager)
Status: Manager Review
    ↓ (requires_approval: hr)
Status: HR Review
    ↓
Status: Approved (Final)
```

### Benefits
- ✅ No code changes needed for new workflows
- ✅ Easy to customize per organization
- ✅ Complete audit trail
- ✅ Flexible approval routing

---

## Design Patterns Used

### 1. Singleton Pattern
- `Database` class ensures single connection
- Prevents multiple database instances

### 2. Factory Pattern
- Router instantiates controllers dynamically
- Flexible controller creation

### 3. Service Layer Pattern
- Business logic separated from controllers
- Reusable across different entry points

### 4. Repository Pattern
- Models abstract database operations
- Easy to swap database layer

### 5. Middleware Pattern
- Request filtering before controller
- Separation of concerns

---

## Naming Conventions

### Files
- Controllers: `PascalCase` + `Controller.php` (e.g., `EmployeeController.php`)
- Models: `PascalCase.php` (e.g., `Employee.php`)
- Views: `snake_case.php` (e.g., `employees/index.php`)

### Database
- Tables: `snake_case` (e.g., `work_items`)
- Columns: `snake_case` (e.g., `created_at`)
- Foreign keys: `{table_singular}_id` (e.g., `user_id`)

### Code
- Classes: `PascalCase`
- Methods: `camelCase`
- Variables: `camelCase`
- Constants: `UPPER_SNAKE_CASE`

---

## Performance Considerations

### Database
- Indexes on frequently queried columns
- Foreign key constraints for referential integrity
- JSON columns for flexible metadata

### Caching
- Session data cached in memory
- Consider Redis for production

### Query Optimization
- Use `LIMIT` on large datasets
- Avoid `SELECT *` when possible
- Use joins efficiently

---

## Future Enhancements

### Planned Features
1. **Email Notifications**: SMTP integration for email alerts
2. **Advanced Search**: Full-text search across work items
3. **File Management**: Document repository with versioning
4. **Calendar View**: Visual calendar for leaves and deadlines
5. **Mobile App**: REST API + React Native mobile app
6. **SSO Integration**: LDAP/Active Directory support
7. **Multi-tenancy**: Support multiple organizations
8. **Reporting Dashboard**: Charts and graphs with Chart.js

### Technical Improvements
1. **API Layer**: RESTful API for third-party integrations
2. **Testing**: PHPUnit test suite
3. **Logging**: Structured logging with Monolog
4. **Caching**: Redis for session and query caching
5. **Queue System**: Background job processing
6. **CDN**: Static asset delivery via CDN

---

## Deployment Considerations

### Production Checklist
- [ ] Change default passwords
- [ ] Enable HTTPS
- [ ] Configure backups
- [ ] Set up monitoring
- [ ] Enable error logging (not display)
- [ ] Configure file upload limits
- [ ] Set up CRON jobs if needed
- [ ] Review audit log retention policy
- [ ] Configure email settings
- [ ] Set proper file permissions

### Recommended Stack
- **Web Server**: Apache 2.4+ or Nginx
- **PHP**: 7.4+ or 8.x
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **SSL**: Let's Encrypt
- **Backup**: Automated daily backups
- **Monitoring**: Uptime monitoring + error tracking

---

## Maintenance

### Regular Tasks
- Review audit logs weekly
- Monitor database size
- Clean up old sessions
- Update leave balances annually
- Archive completed projects
- Review and update workflows as needed

### Updates
- Keep PHP updated for security
- Monitor MySQL for performance
- Review and optimize slow queries
- Update dependencies (Bootstrap, icons)

---

**Nizaam Architecture - Built for Scale, Designed for Simplicity**
