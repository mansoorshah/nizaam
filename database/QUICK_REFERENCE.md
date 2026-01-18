# 🎯 Nizaam - Quick Reference Card

## 🚀 Installation (3 Steps)

```bash
1. Double-click install.bat
   OR manually import database/schema.sql and database/seed.sql

2. Start XAMPP → Apache + MySQL

3. Visit: http://localhost/nizaam/public
```

**Login**: `admin@nizaam.com` / `admin123`

---

## 📋 Main Features Quick Access

| Feature | URL | Access |
|---------|-----|--------|
| Dashboard | `/dashboard` | All Users |
| Employees | `/employees` | All Users |
| Work Items | `/work-items` | All Users |
| Projects | `/projects` | All Users |
| Leave Management | `/leaves` | All Users |
| Request Leave | `/leaves/request` | All Users |
| Submit Expense | `/expenses/create` | All Users |
| Log Timesheet | `/timesheets/create` | All Users |
| Notifications | `/notifications` | All Users |
| Reports | `/reports` | **Admin Only** |
| Audit Log | `/audit` | **Admin Only** |

---

## 👥 User Roles

### Admin
- ✅ All permissions
- ✅ Create/edit employees
- ✅ View all work items
- ✅ Access reports
- ✅ View audit log

### User
- ✅ View own profile
- ✅ Create work items
- ✅ Request leave
- ✅ Submit expenses
- ✅ Log timesheets
- ✅ View assigned work
- ❌ No admin features

---

## 🔄 Workflows

### Task Workflow
```
Open → In Progress → Review → Done/Cancelled
```

### Leave Request Workflow
```
Submitted → Manager Review → HR Review → Approved/Rejected
```

### Expense Workflow
```
Submitted → Manager Review → Finance Review → Approved/Rejected
```

### Timesheet Workflow
```
Draft → Submitted → Manager Review → Approved/Rejected
```

---

## 🎨 Status Colors

| Color | Hex | Usage |
|-------|-----|-------|
| 🔵 Blue | #3B82F6 | Submitted, Open |
| 🟠 Orange | #F59E0B | In Progress, Review |
| 🟣 Purple | #8B5CF6 | Pending Approval |
| 🟢 Green | #10B981 | Approved, Done |
| 🔴 Red | #EF4444 | Rejected |
| ⚫ Gray | #6B7280 | Draft, Cancelled |

---

## 📊 Priority Levels

| Priority | Badge Color | Use Case |
|----------|-------------|----------|
| Low | 🔵 Blue | Non-urgent tasks |
| Medium | 🟦 Primary | Standard tasks |
| High | 🟨 Warning | Important tasks |
| Urgent | 🔴 Danger | Critical items |

---

## 📁 Common Tasks

### Create Employee (Admin)
```
1. Employees → Add Employee
2. Fill: Name, Email, Password, Designation, Department
3. Click Create
→ Leave balances auto-created!
```

### Request Leave
```
1. Leave Management → Request Leave
2. Select: Leave Type, Dates, Reason
3. Submit Request
→ Goes to Manager → HR
```

### Create Task
```
1. Work Items → Create Work Item
2. Fill: Title, Type=Task, Priority, Assignee
3. Create
→ Status = Open
```

### Change Work Item Status
```
1. Open work item
2. Click "Move to [Next Status]"
→ Status history tracked
```

### Add Comment
```
1. Open work item
2. Scroll to Comments
3. Type and Post Comment
→ Notification sent
```

---

## 🔧 Configuration Files

| File | Purpose |
|------|---------|
| `config/database.php` | DB credentials |
| `config/app.php` | App settings |

---

## 🗄️ Database

**Name**: `nizaam`
**Tables**: 17 tables
**Default User**: admin@nizaam.com

### Key Tables
- `users` - Auth
- `employees` - Profiles
- `work_items` - Tasks/Leaves/Expenses
- `workflows` - Workflow config
- `audit_log` - Activity trail

---

## 🛡️ Security

✅ Password hashing (bcrypt)
✅ CSRF tokens
✅ SQL injection safe (PDO)
✅ Role-based access
✅ Session security
✅ Audit logging

---

## 🐛 Troubleshooting

### Can't Login
- Check MySQL is running
- Verify database imported
- Use: `admin@nizaam.com` / `admin123`

### 404 Error
- Use: `http://localhost/nizaam/public` (not `/nizaam`)
- Check `.htaccess` exists

### Database Error
- Check MySQL running
- Verify `nizaam` database exists
- Check credentials in `config/database.php`

---

## 📞 Help & Docs

| Document | Purpose |
|----------|---------|
| `README.md` | Main documentation |
| `SETUP.md` | Setup guide |
| `ARCHITECTURE.md` | System design |
| `DEVELOPER.md` | Customization |
| `FILE_STRUCTURE.md` | File reference |

---

## 🎯 Quick Wins

**First 5 Minutes:**
1. ✅ Login as admin
2. ✅ Create an employee
3. ✅ Create a task
4. ✅ Submit a leave request
5. ✅ Check audit log

**Next 15 Minutes:**
1. ✅ Create a project
2. ✅ Add project members
3. ✅ Assign tasks to project
4. ✅ Submit expense
5. ✅ Log timesheet
6. ✅ View reports

---

## 📈 Project Stats

- ✅ 60+ files
- ✅ 9 major modules
- ✅ 10 controllers
- ✅ 7 models
- ✅ 20+ views
- ✅ 17 database tables
- ✅ 100% core PHP

---

## 🎊 You're Ready!

**Nizaam is fully functional and ready to use!**

Start managing your company operations now! 🚀

---

**Need Help?** Check the docs or review the code - it's well-commented!

**نِظام - Your Company Operating System** ✨
