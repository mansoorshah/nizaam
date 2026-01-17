<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Nizaam' ?> - Company OS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --nizaam-primary: #2563eb;
            --nizaam-primary-dark: #1e40af;
            --nizaam-secondary: #64748b;
            --nizaam-success: #10b981;
            --nizaam-warning: #f59e0b;
            --nizaam-danger: #ef4444;
            --nizaam-info: #3b82f6;
            --nizaam-light: #f8fafc;
            --nizaam-dark: #0f172a;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--nizaam-light);
            color: var(--nizaam-dark);
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-brand .brand-name {
            display: block;
            color: white;
            text-decoration: none;
        }

        .sidebar-brand .brand-tagline {
            font-size: 0.75rem;
            font-weight: 400;
            opacity: 0.8;
            margin-top: 0.25rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            padding: 0.75rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.6;
            font-weight: 600;
        }

        .nav-item {
            margin: 0.25rem 0.75rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
        }

        .nav-link i {
            width: 1.5rem;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .topbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--nizaam-dark);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-badge {
            position: relative;
        }

        .notification-badge .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.65rem;
        }

        .content-area {
            padding: 2rem;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }

        .btn-primary {
            background-color: var(--nizaam-primary);
            border-color: var(--nizaam-primary);
        }

        .btn-primary:hover {
            background-color: var(--nizaam-primary-dark);
            border-color: var(--nizaam-primary-dark);
        }

        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .alert {
            border: none;
            border-radius: 0.75rem;
        }

        .table {
            color: var(--nizaam-dark);
        }

        .table thead th {
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: var(--nizaam-secondary);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: var(--nizaam-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <?php if (Session::has('user')): ?>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <a href="<?= $this->getBaseUrl() ?>/dashboard" class="brand-name">
                نِظام NIZAAM
            </a>
            <div class="brand-tagline">Company Operating System</div>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">Main</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->getBaseUrl() ?>/dashboard">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
            </ul>

            <div class="nav-section">Work</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->getBaseUrl() ?>/work-items">
                        <i class="bi bi-list-task"></i> Work Items
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->getBaseUrl() ?>/projects">
                        <i class="bi bi-folder"></i> Projects
                    </a>
                </li>
            </ul>

            <div class="nav-section">HR</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->getBaseUrl() ?>/employees">
                        <i class="bi bi-people"></i> Employees
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->getBaseUrl() ?>/leaves">
                        <i class="bi bi-calendar-check"></i> Leave Management
                    </a>
                </li>
            </ul>

            <?php if (Session::get('user')['role'] === 'admin'): ?>
            <div class="nav-section">Admin</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->getBaseUrl() ?>/reports">
                        <i class="bi bi-graph-up"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->getBaseUrl() ?>/audit">
                        <i class="bi bi-shield-check"></i> Audit Log
                    </a>
                </li>
            </ul>
            <?php endif; ?>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-title"><?= $title ?? 'Dashboard' ?></div>
            <div class="topbar-actions">
                <a href="<?= $this->getBaseUrl() ?>/notifications" class="btn btn-link text-dark notification-badge">
                    <i class="bi bi-bell fs-5"></i>
                    <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                    <span class="badge bg-danger"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </a>
                
                <div class="dropdown">
                    <button class="btn btn-link text-dark dropdown-toggle user-profile" type="button" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            <?php $empName = Session::get('employee')['full_name'] ?? 'User'; ?>
                            <?= strtoupper(substr($empName, 0, 1)) ?>
                        </div>
                        <span><?= htmlspecialchars($empName) ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php if (Session::get('employee')): ?>
                        <li><a class="dropdown-item" href="<?= $this->getBaseUrl() ?>/employees/<?= Session::get('employee')['id'] ?>">
                            <i class="bi bi-person"></i> My Profile
                        </a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= $this->getBaseUrl() ?>/logout">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if ($success = Session::flash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <!-- Guest layout (no sidebar) -->
            <div class="content-area" style="margin-left: 0;">
            <?php endif; ?>

            <!-- Page content will be inserted here -->
            <?php if (isset($content)) echo $content; ?>
            
            <?php if (Session::has('user')): ?>
        </div>
    </div>
    <?php else: ?>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
