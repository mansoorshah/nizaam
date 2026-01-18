# Nizaam Company OS - Complete Documentation

**Version:** 1.0  
**Last Updated:** January 18, 2026  
**Author:** Nizaam Development Team

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [System Architecture](#system-architecture)
3. [Database Schema](#database-schema)
4. [Core Framework Components](#core-framework-components)
5. [Application Modules](#application-modules)
6. [Authentication & Authorization](#authentication--authorization)
7. [Frontend Architecture](#frontend-architecture)
8. [API & Routing](#api--routing)
9. [Workflow Management](#workflow-management)
10. [Setup & Installation](#setup--installation)
11. [Development Guidelines](#development-guidelines)

---

## 1. Project Overview

### 1.1 Purpose
Nizaam Company OS is an enterprise-grade internal management system designed to streamline company operations including:
- Work item tracking and management
- Project management
- Employee management
- Client relationship management
- Invoice generation and tracking
- Time tracking and reporting
- Company announcements and communications

### 1.2 Technology Stack
- **Backend:** PHP 7.4+ (Custom MVC Framework)
- **Database:** MySQL 5.7+ / MariaDB
- **Frontend:** Bootstrap 5.3.2, JavaScript (ES6+)
- **Libraries:**
  - DataTables 1.13.7 (Advanced table functionality)
  - Quill.js 1.3.7 (Rich text editing)
  - AOS 2.3.1 (Scroll animations)
  - jQuery 3.7.1
  - Bootstrap Icons
  - Google Fonts (Inter)

### 1.3 Key Features
- **Multi-role User System:** Admin, Manager, Employee roles
- **Advanced Work Item Management:** Kanban-style workflow with status transitions
- **Project Portfolio Management:** Full CRUD operations with team assignments
- **Time Tracking:** Manual and automatic time logging
- **Rich Text Support:** Modern WYSIWYG editor for descriptions and comments
- **Responsive Design:** Mobile-first approach with dark mode support
- **Real-time Notifications:** In-app notification system
- **Comprehensive Reporting:** Dashboard analytics and exports

---

## 2. System Architecture

### 2.1 MVC Pattern Implementation

```
nizaam/
├── app/
│   ├── Controllers/        # Business logic layer
│   ├── Models/            # Data access layer
│   ├── Views/             # Presentation layer
│   └── Core/              # Framework core files
├── public/                # Public-facing files
│   ├── index.php         # Application entry point
│   ├── css/              # Custom stylesheets
│   ├── js/               # Custom JavaScript
│   └── uploads/          # User-uploaded files
├── config/
│   └── database.php      # Database configuration
└── vendor/               # Third-party libraries
```

### 2.2 Request Flow

```
1. Browser Request → public/index.php
2. Entry Point → Initialize Autoloader
3. Autoloader → Load Core Components (Router, Database, Session)
4. Router → Parse URL and Match Route
5. Middleware → Execute Auth/Admin/Guest checks
6. Controller → Process Request & Interact with Models
7. Model → Query Database via PDO
8. Controller → Prepare Data & Load View
9. View → Render HTML with Layout Template
10. Response → Send to Browser
```

### 2.3 Directory Structure Details

#### Controllers (`app/Controllers/`)
- **Base Controller:** `Controller.php` - Parent class with view rendering
- **AuthController:** User authentication (login/logout/register)
- **DashboardController:** Dashboard statistics and widgets
- **WorkItemController:** Work item CRUD and status management
- **ProjectController:** Project management operations
- **EmployeeController:** Employee profile management
- **ClientController:** Client relationship management
- **InvoiceController:** Invoice generation and tracking
- **TimeEntryController:** Time tracking functionality
- **ReportController:** Analytics and reporting
- **AnnouncementController:** Company-wide announcements

#### Models (`app/Models/`)
- **Base Model:** `Model.php` - PDO wrapper with query builders
- **User:** Authentication and user profile management
- **WorkItem:** Work item data and relationships
- **Project:** Project data and team assignments
- **Employee:** Employee profiles and department relations
- **Client:** Client information management
- **Invoice:** Invoice generation and payment tracking
- **TimeEntry:** Time log entries
- **Comment:** Comments on work items
- **Notification:** User notification system
- **Department:** Organizational structure
- **Announcement:** Company announcements

#### Views (`app/Views/`)
- **layout.php:** Master layout template
- **dashboard/:** Dashboard views
- **work_items/:** Work item views (index, show, create, edit)
- **projects/:** Project views
- **employees/:** Employee management views
- **auth/:** Login and registration forms

---

## 3. Database Schema

### 3.1 Entity Relationship Overview

```
Users ←→ Employees ←→ Departments
  ↓         ↓
WorkItems ←→ Projects ←→ Clients
  ↓         ↓
Comments  TimeEntries
  ↓
Notifications

Invoices ←→ Clients
Announcements → Users
```

### 3.2 Table Definitions

#### `users` Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK AUTO_INCREMENT | Unique user ID |
| username | VARCHAR(50) UNIQUE | Login username |
| email | VARCHAR(100) UNIQUE | User email |
| password | VARCHAR(255) | Hashed password (bcrypt) |
| role | ENUM('admin','manager','employee') | User role |
| created_at | TIMESTAMP | Account creation date |
| updated_at | TIMESTAMP | Last update timestamp |

#### `employees` Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK AUTO_INCREMENT | Employee ID |
| user_id | INT FK → users.id | Linked user account |
| first_name | VARCHAR(50) | First name |
| last_name | VARCHAR(50) | Last name |
| phone | VARCHAR(20) | Contact number |
| department_id | INT FK → departments.id | Department assignment |
| position | VARCHAR(100) | Job title |
| hire_date | DATE | Employment start date |
| salary | DECIMAL(10,2) | Employee salary |
| is_active | BOOLEAN | Active status |
| created_at | TIMESTAMP | Record creation |
| updated_at | TIMESTAMP | Last update |

#### `departments` Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK AUTO_INCREMENT | Department ID |
| name | VARCHAR(100) | Department name |
| description | TEXT | Department description |
| manager_id | INT FK → employees.id | Department manager |
| created_at | TIMESTAMP | Creation timestamp |

#### `projects` Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK AUTO_INCREMENT | Project ID |
| name | VARCHAR(200) | Project name |
| description | TEXT | Project details |
| client_id | INT FK → clients.id | Associated client |
| status | ENUM('planning','active','on_hold','completed','cancelled') | Project status |
| start_date | DATE | Project start date |
| end_date | DATE | Expected/actual end date |
| budget | DECIMAL(12,2) | Project budget |
| created_by | INT FK → users.id | Creator user ID |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update |

#### `work_items` Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK AUTO_INCREMENT | Work item ID |
| title | VARCHAR(200) | Work item title |
| description | TEXT | Detailed description (supports HTML) |
| project_id | INT FK → projects.id | Associated project |
| assigned_to | INT FK → employees.id | Assigned employee |
| created_by | INT FK → users.id | Creator user ID |
| status | ENUM('backlog','todo','in_progress','in_review','done','cancelled') | Current status |
| priority | ENUM('low','medium','high','critical') | Priority level |
| type | ENUM('task','bug','feature','improvement') | Work item type |
| estimated_hours | DECIMAL(5,2) | Time estimate |
| actual_hours | DECIMAL(5,2) | Actual time spent |
| due_date | DATE | Deadline |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update |

#### `clients` Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK AUTO_INCREMENT | Client ID |
| name | VARCHAR(200) | Client/company name |
| contact_person | VARCHAR(100) | Contact person name |
| email | VARCHAR(100) | Client email |
| phone | VARCHAR(20) | Contact number |
| address | TEXT | Physical address |
| website | VARCHAR(200) | Website URL |
| notes | TEXT | Additional notes |
| created_at | TIMESTAMP | Record creation |
| updated_at | TIMESTAMP | Last update |

#### `invoices` Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK AUTO_INCREMENT | Invoice ID |
| invoice_number | VARCHAR(50) UNIQUE | Invoice number |
| client_id | INT FK → clients.id | Billed client |
| project_id | INT FK → projects.id | Related project |
| issue_date | DATE | Invoice issue date |
| due_date | DATE | Payment due date |
| subtotal | DECIMAL(12,2) | Pre-tax amount |
| tax_amount | DECIMAL(12,2) | Tax amount |
| total_amount | DECIMAL(12,2) | Total amount due |
| status | ENUM('draft','sent','paid','overdue','cancelled') | Payment status |
| notes | TEXT | Invoice notes |
| created_by | INT FK → users.id | Creator user ID |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update |

#### `time_entries` Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK AUTO_INCREMENT | Time entry ID |
| work_item_id | INT FK → work_items.id | Associated work item |
| employee_id | INT FK → employees.id | Employee who logged time |
| hours | DECIMAL(5,2) | Hours worked |
| date | DATE | Work date |
| description | TEXT | Work description |
| created_at | TIMESTAMP | Log timestamp |

#### `comments` Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK AUTO_INCREMENT | Comment ID |
| work_item_id | INT FK → work_items.id | Associated work item |
| user_id | INT FK → users.id | Comment author |
| content | TEXT | Comment content (supports HTML) |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last edit timestamp |

#### `notifications` Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK AUTO_INCREMENT | Notification ID |
| user_id | INT FK → users.id | Recipient user |
| type | VARCHAR(50) | Notification type |
| title | VARCHAR(200) | Notification title |
| message | TEXT | Notification message |
| link | VARCHAR(255) | Associated URL |
| is_read | BOOLEAN | Read status |
| created_at | TIMESTAMP | Creation timestamp |

#### `announcements` Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK AUTO_INCREMENT | Announcement ID |
| title | VARCHAR(200) | Announcement title |
| content | TEXT | Announcement content |
| created_by | INT FK → users.id | Creator user ID |
| is_active | BOOLEAN | Active status |
| created_at | TIMESTAMP | Publication date |
| updated_at | TIMESTAMP | Last update |

### 3.3 Database Relationships

- **One-to-One:** `users` ↔ `employees`
- **One-to-Many:** 
  - `departments` → `employees`
  - `projects` → `work_items`
  - `employees` → `work_items` (assigned)
  - `users` → `comments`
  - `work_items` → `comments`
  - `clients` → `projects`
  - `clients` → `invoices`
- **Many-to-Many:** Projects ↔ Employees (via project assignments)

---

## 4. Core Framework Components

### 4.1 Router (`app/Core/Router.php`)

**Purpose:** URL routing and request dispatching

**Key Methods:**
- `add(string $method, string $path, callable $handler, array $middleware = [])`
- `dispatch(string $uri, string $method)`
- `get/post/put/delete()` - HTTP method shortcuts
- `group(array $attributes, callable $callback)` - Route grouping

**Route Definition Example:**
```php
// Public routes
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);

// Protected routes with middleware
$router->group(['middleware' => ['auth']], function($router) {
    $router->get('/dashboard', [DashboardController::class, 'index']);
    $router->get('/work-items', [WorkItemController::class, 'index']);
    $router->get('/work-items/:id', [WorkItemController::class, 'show']);
});

// Admin-only routes
$router->group(['middleware' => ['auth', 'admin']], function($router) {
    $router->get('/employees', [EmployeeController::class, 'index']);
    $router->post('/employees', [EmployeeController::class, 'store']);
});
```

**URL Pattern Matching:**
- Static routes: `/dashboard`
- Dynamic segments: `/work-items/:id`
- Optional segments: `/projects/:id?`
- Regex patterns: `/users/[0-9]+`

### 4.2 Database (`app/Core/Database.php`)

**Purpose:** PDO database connection management

**Features:**
- Singleton pattern for connection reuse
- Prepared statement support
- Transaction management
- Query logging (development mode)

**Configuration:**
```php
// config/database.php
return [
    'host' => 'localhost',
    'database' => 'nizaam_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
];
```

### 4.3 Model Base Class (`app/Core/Model.php`)

**Purpose:** Active Record pattern implementation

**Key Methods:**
- `find($id)` - Find by primary key
- `all()` - Get all records
- `where($column, $operator, $value)` - Query builder
- `create($data)` - Insert new record
- `update($id, $data)` - Update existing record
- `delete($id)` - Delete record
- `paginate($perPage)` - Pagination support

**Usage Example:**
```php
class WorkItem extends Model {
    protected $table = 'work_items';
    protected $fillable = ['title', 'description', 'status', 'assigned_to'];
    
    public function project() {
        return $this->belongsTo(Project::class, 'project_id');
    }
    
    public function assignee() {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }
}

// Using the model
$workItems = WorkItem::where('status', '=', 'in_progress')
    ->orderBy('priority', 'DESC')
    ->get();
```

### 4.4 Controller Base Class (`app/Core/Controller.php`)

**Purpose:** Base controller functionality

**Key Methods:**
- `view($viewPath, $data = [])` - Render view with layout
- `json($data, $statusCode = 200)` - JSON response
- `redirect($url)` - HTTP redirect
- `getBaseUrl()` - Get application base URL

**Controller Example:**
```php
class WorkItemController extends Controller {
    private $workItemModel;
    
    public function __construct() {
        $this->workItemModel = new WorkItem();
    }
    
    public function index() {
        $workItems = $this->workItemModel->all();
        $this->view('work_items/index', [
            'workItems' => $workItems,
            'title' => 'Work Items'
        ]);
    }
    
    public function show($id) {
        $workItem = $this->workItemModel->find($id);
        if (!$workItem) {
            Session::flash('error', 'Work item not found');
            return $this->redirect('/work-items');
        }
        $this->view('work_items/show', ['workItem' => $workItem]);
    }
}
```

### 4.5 Session Manager (`app/Core/Session.php`)

**Purpose:** Session handling and flash messages

**Key Methods:**
- `start()` - Initialize session
- `set($key, $value)` - Set session value
- `get($key, $default = null)` - Get session value
- `has($key)` - Check if key exists
- `flash($key, $value = null)` - Flash message (available for one request)
- `destroy()` - Destroy session

**Flash Message Usage:**
```php
// In controller
Session::flash('success', 'Work item created successfully');
return $this->redirect('/work-items');

// In view
<?php if ($success = Session::flash('success')): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>
```

### 4.6 Request Helper (`app/Core/Request.php`)

**Purpose:** HTTP request handling

**Key Methods:**
- `method()` - Get request method (GET, POST, etc.)
- `uri()` - Get request URI
- `get($key, $default = null)` - Get query parameter
- `post($key, $default = null)` - Get POST data
- `all()` - Get all input data
- `isAjax()` - Check if AJAX request
- `validate($rules)` - Input validation

---

## 5. Application Modules

### 5.1 Authentication Module

**Files:**
- `app/Controllers/AuthController.php`
- `app/Models/User.php`
- `app/Views/auth/login.php`
- `app/Views/auth/register.php`

**Features:**
- User registration with email verification
- Login with username/email and password
- Password hashing with bcrypt
- "Remember me" functionality
- Session-based authentication
- Logout functionality

**Authentication Flow:**
```
1. User submits credentials → AuthController::login()
2. Validate input → Check if fields are empty
3. Query user → User::findByUsername($username)
4. Verify password → password_verify($password, $hashedPassword)
5. Create session → Session::set('user', $userData)
6. Redirect → Dashboard
```

**Middleware:**
- **AuthMiddleware:** Requires logged-in user
- **GuestMiddleware:** Requires guest (not logged in)
- **AdminMiddleware:** Requires admin role

### 5.2 Dashboard Module

**Files:**
- `app/Controllers/DashboardController.php`
- `app/Views/dashboard/index.php`

**Features:**
- Overview statistics (work items, projects, notifications)
- Recent work items table
- Notifications panel
- Quick action buttons
- Admin-specific widgets (projects overview)

**Dashboard Widgets:**
1. **Statistics Cards:**
   - Total work items assigned to user
   - Work items created by user
   - Unread notifications count
   - Active projects (admin only)

2. **Recent Work Items:**
   - Latest 5 work items assigned to user
   - Status badges
   - Priority indicators
   - Quick view links

3. **Notifications Panel:**
   - Latest 5 notifications
   - Mark as read functionality
   - Relative timestamps

4. **Quick Actions:**
   - Create work item
   - View projects
   - Time entries
   - Reports (admin)

### 5.3 Work Items Module

**Files:**
- `app/Controllers/WorkItemController.php`
- `app/Models/WorkItem.php`
- `app/Views/work_items/index.php`
- `app/Views/work_items/show.php`
- `app/Views/work_items/create.php`
- `app/Views/work_items/edit.php`

**Features:**
- CRUD operations for work items
- Status workflow management
- Priority and type assignment
- Employee assignment
- Time tracking integration
- Comments system
- File attachments (planned)

**Work Item Statuses:**
- `backlog` - Not yet started
- `todo` - Ready to start
- `in_progress` - Currently being worked on
- `in_review` - Awaiting review
- `done` - Completed
- `cancelled` - Cancelled

**Priority Levels:**
- `low` - Low priority
- `medium` - Medium priority
- `high` - High priority
- `critical` - Critical priority

**Work Item Types:**
- `task` - General task
- `bug` - Bug fix
- `feature` - New feature
- `improvement` - Enhancement

**Key Methods:**
- `index()` - List all work items with filtering
- `show($id)` - Display work item details with comments
- `create()` - Show creation form
- `store()` - Save new work item
- `edit($id)` - Show edit form
- `update($id)` - Update existing work item
- `updateStatus($id)` - Change work item status
- `delete($id)` - Delete work item (admin only)

### 5.4 Projects Module

**Files:**
- `app/Controllers/ProjectController.php`
- `app/Models/Project.php`
- `app/Views/projects/index.php`
- `app/Views/projects/show.php`
- `app/Views/projects/create.php`
- `app/Views/projects/edit.php`

**Features:**
- Project CRUD operations
- Client association
- Budget management
- Timeline tracking (start/end dates)
- Team member assignments
- Work items by project
- Project status management

**Project Statuses:**
- `planning` - In planning phase
- `active` - Currently active
- `on_hold` - Temporarily paused
- `completed` - Successfully completed
- `cancelled` - Cancelled

**Key Relationships:**
- Projects → Client (many-to-one)
- Projects → Work Items (one-to-many)
- Projects → Invoices (one-to-many)
- Projects ↔ Employees (many-to-many via assignments)

### 5.5 Employees Module

**Files:**
- `app/Controllers/EmployeeController.php`
- `app/Models/Employee.php`
- `app/Views/employees/index.php`
- `app/Views/employees/show.php`
- `app/Views/employees/create.php`
- `app/Views/employees/edit.php`

**Features:**
- Employee profile management
- Department assignment
- User account linking
- Salary information (admin only)
- Employment history
- Performance metrics

**Employee Fields:**
- Personal information (name, phone, email)
- Department and position
- Hire date
- Salary (encrypted, admin-only)
- Active status
- Linked user account

### 5.6 Clients Module

**Files:**
- `app/Controllers/ClientController.php`
- `app/Models/Client.php`
- `app/Views/clients/index.php`
- `app/Views/clients/show.php`

**Features:**
- Client contact information
- Associated projects list
- Invoice history
- Client notes

### 5.7 Invoices Module

**Files:**
- `app/Controllers/InvoiceController.php`
- `app/Models/Invoice.php`
- `app/Views/invoices/index.php`
- `app/Views/invoices/show.php`
- `app/Views/invoices/create.php`

**Features:**
- Invoice generation
- Payment tracking
- PDF export (planned)
- Tax calculation
- Invoice status management

### 5.8 Time Tracking Module

**Files:**
- `app/Controllers/TimeEntryController.php`
- `app/Models/TimeEntry.php`
- `app/Views/time_entries/index.php`

**Features:**
- Manual time entry
- Work item association
- Daily time logs
- Time reports by employee/project

### 5.9 Reports Module

**Files:**
- `app/Controllers/ReportController.php`
- `app/Views/reports/index.php`

**Features:**
- Work item reports
- Project status reports
- Employee productivity reports
- Time tracking reports
- Financial reports (admin only)

### 5.10 Announcements Module

**Files:**
- `app/Controllers/AnnouncementController.php`
- `app/Models/Announcement.php`
- `app/Views/announcements/index.php`

**Features:**
- Company-wide announcements
- Rich text content
- Active/inactive status
- Creation and edit timestamps

---

## 6. Authentication & Authorization

### 6.1 Role-Based Access Control (RBAC)

**Roles:**
1. **Admin**
   - Full system access
   - Manage employees, departments, projects
   - View all work items
   - Financial reports access
   - System configuration

2. **Manager**
   - Manage assigned projects
   - Create and assign work items
   - View team member performance
   - Approve timesheets
   - Generate reports

3. **Employee**
   - View assigned work items
   - Update work item status
   - Log time entries
   - Add comments
   - View assigned projects

### 6.2 Middleware Implementation

**AuthMiddleware:**
```php
public function handle() {
    if (!Session::has('user')) {
        Session::flash('error', 'Please login to continue');
        header('Location: /login');
        exit;
    }
}
```

**AdminMiddleware:**
```php
public function handle() {
    $user = Session::get('user');
    if (!$user || $user['role'] !== 'admin') {
        Session::flash('error', 'Admin access required');
        header('Location: /dashboard');
        exit;
    }
}
```

### 6.3 Permission Checks

**In Controllers:**
```php
public function delete($id) {
    $this->requireAdmin(); // Throws exception if not admin
    $this->workItemModel->delete($id);
    Session::flash('success', 'Work item deleted');
    return $this->redirect('/work-items');
}
```

**In Views:**
```php
<?php if (Session::get('user')['role'] === 'admin'): ?>
    <a href="/employees/create" class="btn btn-primary">Add Employee</a>
<?php endif; ?>
```

---

## 7. Frontend Architecture

### 7.1 Layout System

**Master Layout (`app/Views/layout.php`):**
- Responsive sidebar navigation
- Top navigation bar with user menu
- Flash message display
- Content area with animations
- Footer
- Dark mode toggle
- Mobile responsive design

**Layout Structure:**
```html
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <!-- Meta tags, title, CSS -->
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Navigation menu -->
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top bar -->
        <div class="topbar">
            <!-- Mobile toggle, theme switcher, user menu -->
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
            <!-- Flash messages -->
            <!-- Page content injected here -->
            <?php echo $content; ?>
        </div>
    </div>
    
    <!-- JavaScript libraries -->
</body>
</html>
```

### 7.2 Design System

**Color Palette:**
```css
:root {
    /* Primary Colors */
    --nizaam-primary: #3b82f6;      /* Blue */
    --nizaam-secondary: #6366f1;    /* Indigo */
    --nizaam-accent: #8b5cf6;       /* Purple */
    --nizaam-success: #10b981;      /* Green */
    --nizaam-danger: #ef4444;       /* Red */
    --nizaam-warning: #f59e0b;      /* Amber */
    --nizaam-info: #06b6d4;         /* Cyan */
    
    /* Neutral Colors */
    --nizaam-gray-50: #f9fafb;
    --nizaam-gray-900: #111827;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
    
    /* Transitions */
    --transition-base: all 0.3s ease;
}
```

**Dark Mode Colors:**
```css
[data-bs-theme="dark"] {
    --nizaam-bg: #0f172a;
    --nizaam-surface: #1e293b;
    --nizaam-text: #e2e8f0;
}
```

**Typography:**
- **Font Family:** Inter (Google Fonts)
- **Headings:** Weight 600-800
- **Body:** Weight 400
- **Small text:** Weight 300

### 7.3 Component Library

**Stat Cards:**
```html
<div class="stat-card" data-aos="fade-up">
    <div class="stat-icon" style="background: var(--nizaam-primary);">
        <i class="bi bi-list-task"></i>
    </div>
    <div class="stat-details">
        <span class="stat-value">24</span>
        <span class="stat-label">Work Items</span>
    </div>
</div>
```

**Enhanced Tables:**
```html
<table class="table data-table">
    <thead>
        <tr>
            <th><i class="bi bi-hash"></i> ID</th>
            <th><i class="bi bi-card-text"></i> Title</th>
            <th><i class="bi bi-flag"></i> Status</th>
        </tr>
    </thead>
    <tbody>
        <!-- Table rows with DataTables functionality -->
    </tbody>
</table>
```

**Rich Text Editor:**
```html
<div class="form-group">
    <label>Description</label>
    <div class="rich-editor"><?= $workItem['description'] ?></div>
    <textarea name="description" style="display:none;"></textarea>
</div>
```

### 7.4 JavaScript Libraries

**DataTables Configuration:**
```javascript
$('.data-table').DataTable({
    responsive: true,
    pageLength: 25,
    order: [[0, 'desc']],
    language: {
        search: "_INPUT_",
        searchPlaceholder: "Search..."
    }
});
```

**Quill Editor Initialization:**
```javascript
function initQuillEditors() {
    document.querySelectorAll('.rich-editor').forEach(editorDiv => {
        const textarea = editorDiv.nextElementSibling;
        const quill = new Quill(editorDiv, {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    ['link', 'code-block']
                ]
            }
        });
        
        // Sync with hidden textarea
        quill.on('text-change', () => {
            textarea.value = quill.root.innerHTML;
        });
    });
}
```

**AOS Animations:**
```javascript
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    offset: 100
});
```

**Dark Mode Toggle:**
```javascript
const themeToggle = document.getElementById('themeToggle');
themeToggle.addEventListener('click', () => {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme);
});
```

### 7.5 Responsive Design

**Breakpoints:**
- **Mobile:** < 768px
- **Tablet:** 768px - 991px
- **Desktop:** ≥ 992px

**Mobile Features:**
- Collapsible sidebar
- Touch-friendly buttons (min 44px)
- Responsive tables with horizontal scroll
- Mobile-optimized forms
- Bottom navigation (planned)

---

## 8. API & Routing

### 8.1 Route Definitions

**Public Routes:**
```php
$router->get('/', [DashboardController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
```

**Protected Routes (Auth Required):**
```php
$router->group(['middleware' => ['auth']], function($router) {
    $router->get('/dashboard', [DashboardController::class, 'index']);
    $router->get('/logout', [AuthController::class, 'logout']);
    
    // Work Items
    $router->get('/work-items', [WorkItemController::class, 'index']);
    $router->get('/work-items/create', [WorkItemController::class, 'create']);
    $router->post('/work-items', [WorkItemController::class, 'store']);
    $router->get('/work-items/:id', [WorkItemController::class, 'show']);
    $router->get('/work-items/:id/edit', [WorkItemController::class, 'edit']);
    $router->post('/work-items/:id', [WorkItemController::class, 'update']);
    $router->post('/work-items/:id/status', [WorkItemController::class, 'updateStatus']);
    
    // Projects
    $router->get('/projects', [ProjectController::class, 'index']);
    $router->get('/projects/:id', [ProjectController::class, 'show']);
    
    // Time Entries
    $router->get('/time-entries', [TimeEntryController::class, 'index']);
    $router->post('/time-entries', [TimeEntryController::class, 'store']);
});
```

**Admin Routes:**
```php
$router->group(['middleware' => ['auth', 'admin']], function($router) {
    // Employees
    $router->get('/employees', [EmployeeController::class, 'index']);
    $router->get('/employees/create', [EmployeeController::class, 'create']);
    $router->post('/employees', [EmployeeController::class, 'store']);
    $router->get('/employees/:id/edit', [EmployeeController::class, 'edit']);
    $router->post('/employees/:id', [EmployeeController::class, 'update']);
    $router->delete('/employees/:id', [EmployeeController::class, 'delete']);
    
    // Projects (Admin CRUD)
    $router->get('/projects/create', [ProjectController::class, 'create']);
    $router->post('/projects', [ProjectController::class, 'store']);
    $router->post('/projects/:id', [ProjectController::class, 'update']);
    $router->delete('/projects/:id', [ProjectController::class, 'delete']);
    
    // Reports
    $router->get('/reports', [ReportController::class, 'index']);
    $router->get('/reports/projects', [ReportController::class, 'projects']);
    $router->get('/reports/employees', [ReportController::class, 'employees']);
});
```

### 8.2 RESTful API Endpoints (Planned)

**Future API Support:**
```
GET    /api/work-items           - List work items
POST   /api/work-items           - Create work item
GET    /api/work-items/:id       - Get work item
PUT    /api/work-items/:id       - Update work item
DELETE /api/work-items/:id       - Delete work item
PATCH  /api/work-items/:id/status - Update status

GET    /api/projects             - List projects
GET    /api/projects/:id         - Get project
GET    /api/projects/:id/work-items - Get project work items

GET    /api/employees            - List employees
GET    /api/employees/:id        - Get employee

POST   /api/time-entries         - Log time entry
GET    /api/time-entries         - List time entries
```

---

## 9. Workflow Management

### 9.1 Work Item Status Transitions

**Allowed Transitions:**
```
backlog → todo
todo → in_progress
in_progress → in_review
in_progress → todo (rollback)
in_review → done
in_review → in_progress (needs changes)
Any status → cancelled (admin only)
```

**Status Change Logic:**
```php
public function updateStatus($id) {
    $newStatus = Request::post('status');
    $workItem = $this->workItemModel->find($id);
    
    // Check if transition is allowed
    $allowedTransitions = [
        'backlog' => ['todo'],
        'todo' => ['in_progress'],
        'in_progress' => ['in_review', 'todo'],
        'in_review' => ['done', 'in_progress']
    ];
    
    if (!in_array($newStatus, $allowedTransitions[$workItem['status']])) {
        Session::flash('error', 'Invalid status transition');
        return $this->redirect('/work-items/' . $id);
    }
    
    $this->workItemModel->update($id, ['status' => $newStatus]);
    
    // Create notification
    $this->createStatusChangeNotification($workItem, $newStatus);
    
    Session::flash('success', 'Status updated successfully');
    return $this->redirect('/work-items/' . $id);
}
```

### 9.2 Notification System

**Notification Triggers:**
- Work item assigned to employee
- Work item status changed
- Comment added to work item
- Project milestone reached
- Invoice created/paid
- New announcement posted

**Notification Creation:**
```php
public function createNotification($userId, $type, $title, $message, $link = null) {
    $notificationModel = new Notification();
    return $notificationModel->create([
        'user_id' => $userId,
        'type' => $type,
        'title' => $title,
        'message' => $message,
        'link' => $link,
        'is_read' => false
    ]);
}
```

---

## 10. Setup & Installation

### 10.1 System Requirements

- **PHP:** 7.4 or higher
- **MySQL:** 5.7+ or MariaDB 10.3+
- **Web Server:** Apache 2.4+ or Nginx
- **Extensions:**
  - PDO
  - PDO_MySQL
  - mbstring
  - JSON
  - OpenSSL

### 10.2 Installation Steps

**1. Clone Repository:**
```bash
git clone https://github.com/yourcompany/nizaam.git
cd nizaam
```

**2. Configure Database:**
```sql
CREATE DATABASE nizaam_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**3. Import Database Schema:**
```bash
mysql -u root -p nizaam_db < database/schema.sql
```

**4. Configure Application:**
```php
// config/database.php
return [
    'host' => 'localhost',
    'database' => 'nizaam_db',
    'username' => 'root',
    'password' => 'your_password',
    'charset' => 'utf8mb4'
];
```

**5. Set Permissions:**
```bash
chmod -R 755 public/uploads
chmod -R 755 storage/logs
```

**6. Configure Web Server:**

**Apache (.htaccess in public/):**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

**Nginx:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

**7. Access Application:**
```
http://localhost/nizaam/public/
```

**8. Default Admin Credentials:**
```
Username: admin
Password: admin123
```

### 10.3 Environment Configuration

**Development Mode:**
```php
// public/index.php
define('APP_ENV', 'development');
define('DEBUG_MODE', true);
```

**Production Mode:**
```php
define('APP_ENV', 'production');
define('DEBUG_MODE', false);
```

---

## 11. Development Guidelines

### 11.1 Coding Standards

**PHP Standards:**
- Follow PSR-12 coding style
- Use type hints where possible
- Document all public methods
- Use meaningful variable names
- Keep methods under 50 lines

**Example:**
```php
/**
 * Create a new work item
 *
 * @param array $data Work item data
 * @return int Created work item ID
 * @throws ValidationException
 */
public function createWorkItem(array $data): int {
    // Validate input
    $this->validate($data, [
        'title' => 'required|max:200',
        'description' => 'required',
        'project_id' => 'required|exists:projects,id',
        'assigned_to' => 'required|exists:employees,id'
    ]);
    
    // Create work item
    return $this->workItemModel->create($data);
}
```

**JavaScript Standards:**
- Use ES6+ syntax
- Prefer const/let over var
- Use arrow functions
- Document complex functions
- Use async/await for promises

**CSS Standards:**
- Use CSS variables for theming
- Mobile-first approach
- BEM naming convention
- Avoid !important
- Use rem/em for sizing

### 11.2 Security Best Practices

**Input Validation:**
```php
// Always validate and sanitize input
$title = htmlspecialchars(trim($_POST['title']));
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
```

**SQL Injection Prevention:**
```php
// Use prepared statements
$stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
```

**XSS Prevention:**
```php
// Escape output in views
<?= htmlspecialchars($user['name']) ?>
```

**CSRF Protection:**
```php
// Generate token
Session::set('csrf_token', bin2hex(random_bytes(32)));

// Validate token
if (!hash_equals(Session::get('csrf_token'), $_POST['csrf_token'])) {
    throw new SecurityException('Invalid CSRF token');
}
```

**Password Security:**
```php
// Hash passwords
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Verify passwords
if (password_verify($inputPassword, $hashedPassword)) {
    // Login successful
}
```

### 11.3 Testing Guidelines

**Unit Testing (Planned):**
```php
// tests/Unit/WorkItemTest.php
class WorkItemTest extends TestCase {
    public function testCreateWorkItem() {
        $workItem = WorkItem::create([
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'todo'
        ]);
        
        $this->assertNotNull($workItem['id']);
        $this->assertEquals('todo', $workItem['status']);
    }
}
```

**Integration Testing (Planned):**
```php
// tests/Integration/WorkItemControllerTest.php
class WorkItemControllerTest extends TestCase {
    public function testCreateWorkItemViaController() {
        $response = $this->post('/work-items', [
            'title' => 'Test Task',
            'description' => 'Test Description'
        ]);
        
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('work_items', ['title' => 'Test Task']);
    }
}
```

### 11.4 Git Workflow

**Branch Strategy:**
- `main` - Production-ready code
- `develop` - Development branch
- `feature/*` - New features
- `bugfix/*` - Bug fixes
- `hotfix/*` - Emergency fixes

**Commit Messages:**
```
feat: Add work item filtering by status
fix: Correct date formatting in dashboard
docs: Update API documentation
refactor: Simplify authentication logic
test: Add unit tests for WorkItem model
```

### 11.5 Database Migration Pattern (Planned)

```php
// database/migrations/2026_01_18_create_work_items_table.php
class CreateWorkItemsTable extends Migration {
    public function up() {
        $sql = "
            CREATE TABLE work_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(200) NOT NULL,
                description TEXT,
                status ENUM('backlog','todo','in_progress','in_review','done','cancelled'),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $this->db->exec($sql);
    }
    
    public function down() {
        $this->db->exec("DROP TABLE IF EXISTS work_items");
    }
}
```

---

## Appendix

### A. Database Indexes

**Recommended Indexes:**
```sql
-- Users
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_email ON users(email);

-- Work Items
CREATE INDEX idx_work_items_status ON work_items(status);
CREATE INDEX idx_work_items_assigned_to ON work_items(assigned_to);
CREATE INDEX idx_work_items_project_id ON work_items(project_id);
CREATE INDEX idx_work_items_created_at ON work_items(created_at);

-- Projects
CREATE INDEX idx_projects_status ON projects(status);
CREATE INDEX idx_projects_client_id ON projects(client_id);

-- Time Entries
CREATE INDEX idx_time_entries_employee_id ON time_entries(employee_id);
CREATE INDEX idx_time_entries_date ON time_entries(date);
```

### B. Common Queries

**Get user's assigned work items:**
```sql
SELECT wi.*, p.name as project_name 
FROM work_items wi
LEFT JOIN projects p ON wi.project_id = p.id
WHERE wi.assigned_to = ?
ORDER BY wi.priority DESC, wi.created_at DESC
```

**Get project statistics:**
```sql
SELECT 
    p.id,
    p.name,
    COUNT(wi.id) as total_work_items,
    SUM(CASE WHEN wi.status = 'done' THEN 1 ELSE 0 END) as completed_items,
    SUM(wi.actual_hours) as total_hours
FROM projects p
LEFT JOIN work_items wi ON p.id = wi.project_id
GROUP BY p.id, p.name
```

### C. Troubleshooting

**Common Issues:**

1. **Database Connection Error:**
   - Check database credentials in `config/database.php`
   - Verify MySQL service is running
   - Check user permissions

2. **404 Errors:**
   - Verify .htaccess file exists in public/
   - Check Apache mod_rewrite is enabled
   - Verify document root points to public/

3. **Session Issues:**
   - Check PHP session.save_path is writable
   - Verify session cookies are enabled
   - Clear browser cookies

4. **Permission Errors:**
   - Set proper file permissions (755 for directories, 644 for files)
   - Check uploads directory is writable
   - Verify PHP user has access

---

**End of Documentation**

*For support or questions, contact: support@nizaam.com*
