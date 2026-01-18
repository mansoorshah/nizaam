# Nizaam - Quick Setup Guide

## 🚀 Getting Started in 5 Minutes

### Step 1: Database Setup

1. Open **phpMyAdmin** in your browser: `http://localhost/phpmyadmin`
2. Create the database:
   - Click **"Import"** tab
   - Choose file: `database/schema.sql`
   - Click **"Go"**
3. Seed the data:
   - Stay in **"Import"** tab
   - Choose file: `database/seed.sql`
   - Click **"Go"**

### Step 2: Start Services

1. Open **XAMPP Control Panel**
2. Start **Apache**
3. Start **MySQL**

### Step 3: Access Application

Visit: `http://localhost/nizaam/public`

**Login Credentials:**
- Email: `admin@nizaam.com`
- Password: `admin123`

---

## 📋 What's Included

### Core Features

✅ **Authentication & Security**
- Email/password login with CSRF protection
- Role-based access (Admin/User)
- Password hashing (bcrypt)
- Session management

✅ **Employee Management**
- Complete employee profiles
- Department & manager structure
- Employment status tracking
- Automatic leave balance initialization

✅ **Work Item System**
- Tasks, Leave Requests, Expenses, Timesheets
- Priority levels and due dates
- Project association
- Comments and attachments support

✅ **Workflow Engine**
- Configurable workflows by type
- Status transitions with approvals
- Complete history tracking
- Visual timeline

✅ **Leave Management**
- Leave types (Annual, Sick, Unpaid, Emergency)
- Automatic quota assignment by designation
- Balance tracking
- Multi-level approval workflow

✅ **Projects**
- Project creation and ownership
- Team member management
- Work item grouping

✅ **Notifications**
- In-app notifications
- Assignment alerts
- Status change notifications
- Read/unread tracking

✅ **Reporting (Admin)**
- Work items by status/type
- Employee workload analysis
- Leave usage reports

✅ **Audit Log (Admin)**
- Complete activity tracking
- Filter by entity, action, date
- Append-only for compliance

---

## 🎨 UI Features

- **Modern Design**: Clean, professional interface
- **Bootstrap 5**: Responsive and mobile-friendly
- **Nizaam Branding**: Consistent color scheme and typography
- **Status Badges**: Color-coded status indicators
- **Professional Layout**: Sidebar navigation with topbar

---

## 📁 Project Structure

```
nizaam/
├── app/
│   ├── Controllers/        # Request handlers (Auth, Dashboard, Employee, etc.)
│   ├── Models/            # Database models (User, Employee, WorkItem, etc.)
│   ├── Services/          # Business logic (WorkflowService, LeaveService)
│   ├── Middleware/        # Auth, Admin, Guest middleware
│   ├── Views/             # UI templates (login, dashboard, employees, etc.)
│   └── Core files         # Router, Controller, Model, Database, Session, Request
├── config/                # Database and app configuration
├── database/              # SQL schema and seed files
├── public/                # Web root (index.php, .htaccess)
└── storage/uploads/       # File uploads directory
```

---

## 🔐 Security Features

- ✅ Password hashing (bcrypt)
- ✅ CSRF token validation
- ✅ SQL injection prevention (PDO prepared statements)
- ✅ Role-based access control
- ✅ Session regeneration on login
- ✅ Input validation
- ✅ Audit logging

---

## 🛠️ Configuration

### Database Configuration
Edit `config/database.php`:
```php
'host' => 'localhost',
'dbname' => 'nizaam',
'username' => 'root',
'password' => '',
```

### Application Configuration
Edit `config/app.php`:
```php
'name' => 'Nizaam',
'url' => 'http://localhost/nizaam',
'timezone' => 'UTC',
'session_lifetime' => 120,
'upload_max_size' => 5242880, // 5MB
```

---

## 📖 Usage Examples

### Creating an Employee

1. Go to **Employees** → **Add Employee**
2. Fill in:
   - Employee ID (e.g., EMP002)
   - Full Name
   - Email
   - Password
   - Designation (determines leave quota)
   - Department
   - Manager (optional)
   - Join Date
3. Click **Create Employee**
4. Leave balances are auto-initialized!

### Requesting Leave

1. Go to **Leave Management** → **Request Leave**
2. Check your balance in the sidebar
3. Select:
   - Leave type
   - Start and end dates
   - Reason
4. Click **Submit Request**
5. Workflow automatically starts: Submitted → Manager → HR → Approved

### Creating a Task

1. Go to **Work Items** → **Create Work Item**
2. Fill in:
   - Type: Task
   - Title and description
   - Priority
   - Assign to employee
   - Due date (optional)
   - Project (optional)
3. Click **Create Work Item**
4. Status is automatically set to "Open"

### Viewing Reports (Admin Only)

1. Go to **Reports**
2. View:
   - Work items by status (with percentages)
   - Work items by type
   - Top employee workload
3. Export to CSV (if needed)

### Checking Audit Log (Admin Only)

1. Go to **Audit Log**
2. Filter by:
   - Entity type (User, Employee, Work Item, etc.)
   - Action (Create, Update, Delete, Login, etc.)
   - Date range
3. View complete activity history

---

## 🔄 Workflows

### Leave Request Flow
```
Submitted → Manager Review → HR Review → Approved/Rejected
```

### Expense Flow
```
Submitted → Manager Review → Finance Review → Approved/Rejected
```

### Task Flow
```
Open → In Progress → Review → Done/Cancelled
```

### Timesheet Flow
```
Draft → Submitted → Manager Review → Approved/Rejected
```

---

## 🎯 Next Steps

1. **Change Default Password**: Go to profile settings
2. **Add Employees**: Create your team
3. **Create Projects**: Organize work by project
4. **Test Workflows**: Submit leave requests, create tasks
5. **Review Reports**: Check system usage

---

## 🐛 Troubleshooting

### "Database connection failed"
- Check MySQL is running in XAMPP
- Verify database name is `nizaam`
- Check credentials in `config/database.php`

### "404 Not Found"
- Ensure you're accessing `http://localhost/nizaam/public` (not just `/nizaam`)
- Check `.htaccess` files exist
- Verify Apache `mod_rewrite` is enabled

### "Permission Denied"
- Check `storage/uploads` folder exists and is writable
- Verify Apache has read access to the project

### Can't Login
- Ensure database is seeded (`seed.sql`)
- Default credentials: `admin@nizaam.com` / `admin123`
- Check password is entered correctly (case-sensitive)

---

## 📞 Support

Review the main `README.md` for detailed documentation.

---

**Built with ❤️ in Core PHP**
**نِظام - Bringing Order to Your Operations**
