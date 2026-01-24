# Nizaam (نِظام) - Company Operating System

A modern, workflow-driven Company OS built with core PHP, featuring employee management, work items, approvals, HR operations, and comprehensive audit logging.

## Features

### Core Modules

- **Authentication & Access Control**
  - Secure email/password authentication
  - Role-based access (Admin, User)
  - Session management with CSRF protection

- **Employee System**
  - Complete employee profiles
  - Department and reporting structure
  - Employment status tracking
  - Manager-employee relationships

- **Work Engine**
  - Generalized WorkItem entity
  - Support for tasks, leave requests, expenses, timesheets
  - Priority and due date management
  - Project association
  - Comments and attachments
  - **File upload and management** (NEW!)
  - Document storage and download

- **Workflow Engine**
  - Configurable workflows per work type
  - Status transitions with approval rules
  - Complete status history
  - Visual timeline

- **HR Operations**
  - **Leave Management**: Quotas, balances, approval workflows, **calendar view** (NEW!)
  - **Expense Reimbursements**: Attachment support, multi-level approval
  - **Timesheets**: Weekly submission with approval, **list view with statistics** (NEW!)

- **Projects**
  - Lightweight project grouping
  - Project members and ownership
  - Work item association

- **Audit & Compliance**
  - Append-only audit log
  - Complete activity tracking
  - Admin-only access
  - Compliance-ready

- **Notifications**
  - In-app notification system
  - Assignment and status change alerts
  - Read/unread tracking

- **Reporting**
  - Work items by status and type
  - Employee workload analysis
  - Leave usage reports
  - **Interactive charts with Chart.js** (NEW!)
  - **CSV export for all reports** (NEW!)
  - Professional data visualization

## Technology Stack

- **Backend**: Core PHP (no frameworks)
- **Database**: MySQL with PDO
- **Frontend**: Bootstrap 5, modern CSS
- **Architecture**: Custom MVC
- **Security**: Password hashing, CSRF tokens, input validation

## Installation

### Prerequisites

- XAMPP (Apache + MySQL + PHP 7.4+)
- Web browser

### Setup Steps

1. **Clone or copy the project** to `c:\xampp\htdocs\nizaam`

2. **Create the database**:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Click "Import" tab
   - Select `database/schema.sql`
   - Click "Go"

3. **Seed initial data**:
   - In phpMyAdmin, select the `nizaam` database
   - Click "Import" tab
   - Select `database/seed.sql`
   - Click "Go"

4. **Start Apache and MySQL**:
   - Open XAMPP Control Panel
   - Start Apache
   - Start MySQL

5. **Access the application**:
   - Navigate to: http://localhost/nizaam/public
   - Login with default credentials:
     - Email: `admin@nizaam.com`
     - Password: `admin123`

## Configuration

Edit configuration files in the `config/` directory:

- `database.php` - Database connection settings
- `app.php` - Application settings (timezone, upload limits, etc.)

## Project Structure

```
nizaam/
├── app/
│   ├── Controllers/       # Request handlers
│   ├── Models/           # Database models
│   ├── Services/         # Business logic
│   ├── Middleware/       # Authentication & authorization
│   ├── Views/            # UI templates
│   ├── Controller.php    # Base controller
│   ├── Model.php         # Base model
│   ├── Router.php        # URL routing
│   ├── Database.php      # Database connection
│   ├── Session.php       # Session management
│   ├── Request.php       # Request handling
│   └── routes.php        # Route definitions
├── config/               # Configuration files
├── database/             # SQL schema and seeds
├── public/               # Public web root
│   ├── index.php         # Entry point
│   └── .htaccess         # URL rewriting
└── storage/
    └── uploads/          # File uploads
```

## Security Features

- Password hashing with bcrypt
- CSRF token validation
- SQL injection prevention (PDO prepared statements)
- Role-based access control
- Session regeneration on login
- Input validation and sanitization
- Audit logging for compliance

## Default Credentials

After seeding:
- **Admin**: admin@nizaam.com / admin123

**⚠️ Important**: Change the default password immediately in production!

## Workflows

### Leave Request Workflow
1. **Submitted** → Employee submits leave request
2. **Manager Review** → Manager approves/rejects
3. **HR Review** → HR final approval
4. **Approved/Rejected** → Final status

### Expense Workflow
1. **Submitted** → Employee submits expense
2. **Manager Review** → Manager approves/rejects
3. **Finance Review** → Finance approves payment
4. **Approved/Rejected** → Final status

### Task Workflow
1. **Open** → Task created
2. **In Progress** → Work started
3. **Review** → Work completed, needs review
4. **Done/Cancelled** → Final status

## Usage Guide

### Creating Employees

1. Go to **Employees** → **Add Employee**
2. Fill in employee details
3. Leave balances are automatically initialized based on designation

### Creating Work Items

1. Go to **Work Items** → **Create Work Item**
2. Select type, priority, and assignee
3. Work item enters initial workflow status automatically

### Requesting Leave

1. Go to **Leave Management** → **Request Leave**
2. Check your leave balance
3. Select dates and reason
4. Submit for approval

### Managing Projects

1. Go to **Projects** → **Create Project**
2. Add project members
3. Associate work items with projects

### Viewing Reports (Admin Only)

1. Go to **Reports**
2. View work item statistics, employee workload, leave usage
3. Export to CSV

### Audit Log (Admin Only)

1. Go to **Audit Log**
2. Filter by entity type, action, or date range
3. Review all system activities

## Design Philosophy

**Nizaam** (نِظام) means "system" or "order" in Arabic, reflecting the application's goal to bring structure and efficiency to company operations.

### Key Principles

- **Modern UI**: Clean, professional design comparable to BambooHR/ClickUp
- **No ERP Bloat**: Focused feature set for essential operations
- **Workflow-Driven**: Everything flows through configurable workflows
- **Audit-Ready**: Complete activity tracking for compliance
- **Maintainable Code**: Clean MVC architecture, no business logic in views

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Troubleshooting

### Database Connection Error
- Check MySQL is running in XAMPP
- Verify database credentials in `config/database.php`
- Ensure `nizaam` database exists

### 404 Errors
- Check Apache mod_rewrite is enabled
- Verify `.htaccess` files are present
- Access via `http://localhost/nizaam/public` (not root folder)

### Permission Errors
- Ensure `storage/uploads` is writable
- Check Apache has read access to project files

## Future Enhancements

- Dark mode support
- Email notifications
- Advanced reporting with charts
- Document management
- Performance reviews
- Attendance tracking
- Payroll integration

## License

This is a demonstration project. Customize as needed for your organization.

## Support

For issues or questions, review the code comments and architectural patterns.

---

**Built with ❤️ using Core PHP**
