<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Required - Nizaam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .setup-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 3rem 2.5rem;
            max-width: 600px;
            text-align: center;
        }

        .brand-logo {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 1rem;
        }

        .icon-warning {
            font-size: 4rem;
            color: #f59e0b;
            margin-bottom: 1.5rem;
        }

        .btn-logout {
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            margin-top: 1rem;
        }

        .btn-logout:hover {
            background: #dc2626;
        }
    </style>
</head>
<body>
    <div class="setup-card">
        <div class="brand-logo">نِظام NIZAAM</div>
        
        <div class="icon-warning">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        
        <h2 class="mb-3">Employee Profile Required</h2>
        
        <div class="alert alert-warning text-start">
            <p class="mb-2"><strong>Your account is missing an employee profile.</strong></p>
            <p class="mb-0">Please contact your administrator to create an employee profile for your account.</p>
        </div>

        <div class="text-muted small mt-4 mb-3">
            <p><strong>For Administrators:</strong></p>
            <ol class="text-start">
                <li>Go to <strong>Employees</strong> → <strong>Add Employee</strong></li>
                <li>Link this user account (<?= htmlspecialchars(Session::get('user')['email'] ?? '') ?>)</li>
                <li>Fill in employee details and save</li>
            </ol>
        </div>

        <a href="<?= dirname($_SERVER['SCRIPT_NAME']) === '/' ? '' : dirname($_SERVER['SCRIPT_NAME']) ?>/logout" class="btn btn-logout">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
