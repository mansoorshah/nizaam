<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Nizaam' ?> - Company OS</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%234169E1' rx='8'/><g fill='white'><circle cx='50' cy='25' r='6'/><circle cx='30' cy='55' r='6'/><circle cx='50' cy='55' r='6'/><circle cx='70' cy='55' r='6'/><line x1='50' y1='31' x2='50' y2='49' stroke='white' stroke-width='3'/><line x1='50' y1='55' x2='30' y2='55' stroke='white' stroke-width='3'/><line x1='50' y1='55' x2='70' y2='55' stroke='white' stroke-width='3'/><rect x='45' y='70' width='10' height='20' fill='white'/><rect x='25' y='70' width='10' height='20' fill='white'/><rect x='65' y='70' width='10' height='20' fill='white'/></g></svg>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <!-- Quill Rich Text Editor -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    
    <!-- Quill Image Resize Module -->
    <link href="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.css" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            /* Nizaam Brand Colors - Modern Enterprise Grade */
            --nizaam-primary: #3b82f6;
            --nizaam-primary-hover: #2563eb;
            --nizaam-primary-light: #dbeafe;
            --nizaam-primary-dark: #1e40af;
            
            --nizaam-secondary: #6366f1;
            --nizaam-secondary-hover: #4f46e5;
            --nizaam-secondary-light: #e0e7ff;
            
            --nizaam-accent: #8b5cf6;
            --nizaam-accent-hover: #7c3aed;
            
            --nizaam-success: #10b981;
            --nizaam-success-light: #d1fae5;
            --nizaam-warning: #f59e0b;
            --nizaam-warning-light: #fef3c7;
            --nizaam-danger: #ef4444;
            --nizaam-danger-light: #fee2e2;
            --nizaam-info: #06b6d4;
            --nizaam-info-light: #cffafe;
            
            /* Neutral Colors */
            --nizaam-gray-50: #f9fafb;
            --nizaam-gray-100: #f3f4f6;
            --nizaam-gray-200: #e5e7eb;
            --nizaam-gray-300: #d1d5db;
            --nizaam-gray-400: #9ca3af;
            --nizaam-gray-500: #6b7280;
            --nizaam-gray-600: #4b5563;
            --nizaam-gray-700: #374151;
            --nizaam-gray-800: #1f2937;
            --nizaam-gray-900: #111827;
            
            /* Layout */
            --sidebar-width: 280px;
            --topbar-height: 70px;
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            
            /* Transitions */
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-bs-theme="dark"] {
            --nizaam-gray-50: #1e293b;
            --nizaam-gray-100: #0f172a;
            --nizaam-gray-900: #f1f5f9;
            
            --nizaam-primary-light: #1e3a8a;
            --nizaam-secondary-light: #312e81;
            --nizaam-success-light: #064e3b;
            --nizaam-warning-light: #78350f;
            --nizaam-danger-light: #7f1d1d;
            --nizaam-info-light: #164e63;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--nizaam-gray-50);
            color: var(--nizaam-gray-900);
            transition: background-color var(--transition-base), color var(--transition-base);
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ========================================
           SIDEBAR STYLES - Modern & Sleek
        ======================================== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e40af 0%, #3b82f6 50%, #6366f1 100%);
            color: white;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1050;
            box-shadow: var(--shadow-xl);
            transition: transform var(--transition-base);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar-brand {
            padding: 1.75rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand .brand-name {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            letter-spacing: -0.5px;
            transition: transform var(--transition-fast);
        }

        .sidebar-brand .brand-name:hover {
            transform: scale(1.02);
        }

        .sidebar-brand .brand-logo {
            width: 36px;
            height: 36px;
            margin-right: 0.75rem;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--nizaam-primary);
        }

        .sidebar-brand .brand-tagline {
            font-size: 0.7rem;
            font-weight: 500;
            opacity: 0.8;
            margin-top: 0.5rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .sidebar-nav {
            padding: 1.5rem 0;
        }

        .nav-section {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            opacity: 0.7;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            border-radius: 12px;
            transition: all var(--transition-fast);
            font-weight: 500;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: white;
            transform: scaleY(0);
            transition: transform var(--transition-fast);
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(4px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .nav-link.active::before {
            transform: scaleY(1);
        }

        .nav-link i {
            width: 1.75rem;
            margin-right: 1rem;
            font-size: 1.15rem;
            transition: transform var(--transition-fast);
        }

        .nav-link:hover i {
            transform: scale(1.1);
        }

        /* ========================================
           MAIN CONTENT AREA
        ======================================== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: var(--nizaam-gray-50);
            transition: margin-left var(--transition-base);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* ========================================
           TOPBAR - Modern Header
        ======================================== */
        .topbar {
            background: var(--bs-body-bg);
            padding: 1rem 2rem;
            height: var(--topbar-height);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--bs-border-color);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1040;
            backdrop-filter: blur(10px);
            background: rgba(var(--bs-body-bg-rgb), 0.95);
        }

        .topbar-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--nizaam-gray-900);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-title i {
            color: var(--nizaam-primary);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-actions .btn-link {
            color: var(--nizaam-gray-600);
            padding: 0.5rem;
            border-radius: 10px;
            transition: all var(--transition-fast);
            position: relative;
        }

        .topbar-actions .btn-link:hover {
            background: var(--nizaam-gray-100);
            color: var(--nizaam-primary);
        }

        [data-bs-theme="dark"] .topbar-actions .btn-link:hover {
            background: var(--nizaam-gray-800);
        }

        .notification-badge {
            position: relative;
        }

        .notification-badge .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            font-size: 0.65rem;
            padding: 0.25em 0.5em;
            border-radius: 10px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* ========================================
           CONTENT AREA - Enhanced Spacing
        ======================================== */
        .content-area {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .content-area {
                padding: 1rem;
            }
        }

        /* ========================================
           CARDS - Modern Elevated Design
        ======================================== */
        .card {
            border: 1px solid var(--bs-border-color);
            border-radius: 16px;
            background: var(--bs-body-bg);
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            transition: all var(--transition-base);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .card-header {
            background: var(--bs-body-bg);
            border-bottom: 1px solid var(--bs-border-color);
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        /* Dark mode card header with brand gradient */
        [data-bs-theme="dark"] .card-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #2563eb 100%);
            color: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid rgba(59, 130, 246, 0.3);
        }
        
        [data-bs-theme="dark"] .card-header a,
        [data-bs-theme="dark"] .card-header i,
        [data-bs-theme="dark"] .card-header .btn,
        [data-bs-theme="dark"] .card-header h1,
        [data-bs-theme="dark"] .card-header h2,
        [data-bs-theme="dark"] .card-header h3,
        [data-bs-theme="dark"] .card-header h4,
        [data-bs-theme="dark"] .card-header h5,
        [data-bs-theme="dark"] .card-header h6 {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        [data-bs-theme="dark"] .card-header .btn:hover,
        [data-bs-theme="dark"] .card-header a:hover {
            color: white !important;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-footer {
            background: var(--nizaam-gray-50);
            border-top: 1px solid var(--bs-border-color);
            padding: 1rem 1.5rem;
        }

        [data-bs-theme="dark"] .card-footer {
            background: var(--nizaam-gray-800);
        }

        /* Stat Cards */
        .stat-card {
            border-radius: 16px;
            padding: 1.5rem;
            background: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            transition: all var(--transition-base);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--stat-color, var(--nizaam-primary));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform var(--transition-base);
        }

        .stat-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--nizaam-gray-600);
            font-weight: 500;
        }

        /* ========================================
           BUTTONS - Enhanced Styling
        ======================================== */
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all var(--transition-fast);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--nizaam-primary) 0%, var(--nizaam-secondary) 100%);
            color: white;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--nizaam-primary-hover) 0%, var(--nizaam-secondary-hover) 100%);
            box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border: 2px solid var(--nizaam-primary);
            color: var(--nizaam-primary);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--nizaam-primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
        }

        .btn-lg {
            padding: 0.875rem 1.75rem;
            font-size: 1rem;
        }

        /* ========================================
           BADGES & STATUS INDICATORS
        ======================================== */
        .status-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .badge {
            border-radius: 8px;
            padding: 0.375rem 0.75rem;
            font-weight: 600;
        }

        /* ========================================
           ALERTS - Modern Design
        ======================================== */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            border-left: 4px solid;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            background: var(--nizaam-success-light);
            border-left-color: var(--nizaam-success);
            color: #065f46;
        }

        .alert-danger {
            background: var(--nizaam-danger-light);
            border-left-color: var(--nizaam-danger);
            color: #991b1b;
        }

        .alert-warning {
            background: var(--nizaam-warning-light);
            border-left-color: var(--nizaam-warning);
            color: #92400e;
        }

        .alert-info {
            background: var(--nizaam-info-light);
            border-left-color: var(--nizaam-info);
            color: #0e7490;
        }

        [data-bs-theme="dark"] .alert-success {
            background: var(--nizaam-success-light);
            color: #86efac;
        }

        [data-bs-theme="dark"] .alert-danger {
            background: var(--nizaam-danger-light);
            color: #fca5a5;
        }

        [data-bs-theme="dark"] .alert-warning {
            background: var(--nizaam-warning-light);
            color: #fcd34d;
        }

        [data-bs-theme="dark"] .alert-info {
            background: var(--nizaam-info-light);
            color: #67e8f9;
        }

        /* ========================================
           TABLES - Modern DataTables Styling
        ======================================== */
        .table {
            color: var(--nizaam-gray-900);
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            border-bottom: 2px solid var(--nizaam-gray-200);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.8px;
            color: var(--nizaam-gray-600);
            padding: 1rem;
            background: var(--nizaam-gray-50);
        }

        [data-bs-theme="dark"] .table thead th {
            background: var(--nizaam-gray-800);
            border-bottom-color: var(--nizaam-gray-700);
        }

        .table tbody tr {
            transition: all var(--transition-fast);
        }

        .table tbody tr:hover {
            background: var(--nizaam-gray-50);
            box-shadow: var(--shadow-sm);
        }

        [data-bs-theme="dark"] .table tbody tr:hover {
            background: var(--nizaam-gray-800);
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--nizaam-gray-100);
        }

        [data-bs-theme="dark"] .table tbody td {
            border-bottom-color: var(--nizaam-gray-800);
        }

        /* DataTables Custom Styling */
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 10px;
            border: 1px solid var(--bs-border-color);
            padding: 0.5rem 1rem;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 10px;
            border: 1px solid var(--bs-border-color);
            padding: 0.375rem 2rem 0.375rem 0.75rem;
        }

        /* ========================================
           FORMS - Enhanced Input Styling
        ======================================== */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid var(--nizaam-gray-300);
            padding: 0.625rem 1rem;
            font-size: 0.9rem;
            transition: all var(--transition-fast);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--nizaam-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--nizaam-gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        [data-bs-theme="dark"] .form-label {
            color: var(--nizaam-gray-300);
        }

        /* ========================================
           RICH TEXT EDITOR STYLING - Enhanced
        ======================================== */
        .ql-container {
            border-radius: 0 0 10px 10px;
            border: 1px solid var(--bs-border-color);
            border-top: none;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            background: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        .ql-editor {
            min-height: 120px;
            color: var(--bs-body-color);
        }

        .ql-editor.ql-blank::before {
            color: var(--nizaam-gray-500);
            font-style: italic;
        }

        [data-bs-theme="dark"] .ql-editor.ql-blank::before {
            color: var(--nizaam-gray-600);
        }

        .ql-toolbar {
            border-radius: 10px 10px 0 0;
            border: 1px solid var(--bs-border-color);
            border-bottom: none;
            background: var(--nizaam-gray-50);
            padding: 0.5rem;
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        [data-bs-theme="dark"] .ql-toolbar {
            background: var(--nizaam-gray-800);
            border-color: var(--nizaam-gray-700);
        }

        /* Quill toolbar buttons */
        .ql-toolbar button,
        .ql-toolbar .ql-picker-label {
            padding: 0.25rem;
            margin: 0 2px;
        }

        .ql-toolbar .ql-stroke {
            stroke: var(--bs-body-color);
        }

        .ql-toolbar .ql-fill {
            fill: var(--bs-body-color);
        }

        .ql-toolbar button:hover,
        .ql-toolbar .ql-picker-label:hover {
            color: var(--nizaam-primary);
        }

        .ql-toolbar button:hover .ql-stroke {
            stroke: var(--nizaam-primary);
        }

        .ql-toolbar button:hover .ql-fill {
            fill: var(--nizaam-primary);
        }

        .ql-toolbar button.ql-active,
        .ql-toolbar .ql-picker-label.ql-active {
            color: var(--nizaam-primary);
        }

        .ql-toolbar button.ql-active .ql-stroke {
            stroke: var(--nizaam-primary);
        }

        .ql-toolbar button.ql-active .ql-fill {
            fill: var(--nizaam-primary);
        }

        /* Quill picker dropdowns */
        .ql-toolbar .ql-picker-options {
            background: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
        }

        .ql-toolbar .ql-picker-item {
            color: var(--bs-body-color);
        }

        .ql-toolbar .ql-picker-item:hover {
            color: var(--nizaam-primary);
            background: var(--nizaam-primary-light);
        }

        /* Make toolbar items inline and compact */
        .ql-toolbar .ql-formats {
            margin-right: 8px;
            display: inline-flex;
            align-items: center;
        }

        .ql-toolbar::-webkit-scrollbar {
            height: 6px;
        }

        .ql-toolbar::-webkit-scrollbar-thumb {
            background: var(--nizaam-gray-300);
            border-radius: 3px;
        }

        [data-bs-theme="dark"] .ql-toolbar::-webkit-scrollbar-thumb {
            background: var(--nizaam-gray-700);
        }

        /* Rich editor wrapper */
        .rich-editor {
            border-radius: 10px;
        }

        /* ========================================
           COMMENTS SECTION STYLING
        ======================================== */
        .comments-list {
            margin-bottom: 1rem;
        }

        .comment-item {
            transition: all var(--transition-fast);
        }

        .comment-item:hover {
            background: var(--nizaam-gray-50);
            border-radius: 8px;
            padding: 0.5rem !important;
            margin: 0 -0.5rem;
        }

        [data-bs-theme="dark"] .comment-item:hover {
            background: var(--nizaam-gray-800);
        }

        .comment-content {
            color: var(--bs-body-color);
            line-height: 1.6;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .comment-content p {
            margin-bottom: 0.5rem;
            word-wrap: break-word;
        }

        .comment-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 0.5rem 0;
            display: block;
        }

        .comment-content p img {
            display: inline-block;
            vertical-align: middle;
        }

        /* Make sure Quill editor content displays images properly */
        .ql-editor img {
            max-width: 100%;
            height: auto;
        }

        /* Ensure rich content areas show images */
        .rich-content img, 
        [class*="content"] img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }

        .comment-form {
            border-top: 1px solid var(--bs-border-color);
            padding-top: 1rem;
        }

        /* Comment author name styling for dark mode visibility */
        .comment-item strong {
            color: var(--bs-body-color) !important;
        }

        [data-bs-theme="dark"] .comment-item strong {
            color: var(--bs-light) !important;
        }

        /* ========================================
           USER PROFILE & DROPDOWN
        ======================================== */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            transition: all var(--transition-fast);
            text-decoration: none;
            border: 1px solid transparent;
        }

        .user-profile:hover {
            background: rgba(59, 130, 246, 0.08);
            border-color: rgba(59, 130, 246, 0.2);
        }

        [data-bs-theme="dark"] .user-profile:hover {
            background: rgba(59, 130, 246, 0.12);
            border-color: rgba(59, 130, 246, 0.3);
        }
        
        .user-profile span {
            color: var(--nizaam-text);
            font-weight: 500;
            font-size: 0.9375rem;
        }
        
        [data-bs-theme="dark"] .user-profile span {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .user-profile::after {
            color: var(--nizaam-text);
        }
        
        [data-bs-theme="dark"] .user-profile::after {
            color: rgba(255, 255, 255, 0.7);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.125rem;
        }
        
        [data-bs-theme="dark"] .user-avatar {
            background: #3b82f6;
        }
        
        .user-avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--nizaam-primary) 0%, var(--nizaam-secondary) 100%);
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .dropdown-menu {
            border: 1px solid var(--bs-border-color);
            box-shadow: var(--shadow-lg);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            background: var(--bs-body-bg);
        }
        
        [data-bs-theme="dark"] .dropdown-menu {
            background: #1e293b;
            border-color: rgba(59, 130, 246, 0.2);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            transition: all var(--transition-fast);
            color: var(--nizaam-text);
        }
        
        [data-bs-theme="dark"] .dropdown-item {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .dropdown-item:hover {
            background: rgba(59, 130, 246, 0.1);
            color: var(--nizaam-primary);
        }
        
        [data-bs-theme="dark"] .dropdown-item:hover {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
        }
        
        .dropdown-item i {
            width: 20px;
            margin-right: 0.5rem;
        }

        .dropdown-item:hover {
            background: var(--nizaam-primary-light);
            color: var(--nizaam-primary);
        }

        [data-bs-theme="dark"] .dropdown-item:hover {
            background: var(--nizaam-primary-dark);
            color: white;
        }

        /* ========================================
           LOADING & ANIMATIONS
        ======================================== */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }

        /* Page transitions */
        .page-transition {
            animation: fadeInUp 0.4s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ========================================
           UTILITY CLASSES
        ======================================== */
        .text-gradient {
            background: linear-gradient(135deg, var(--nizaam-primary) 0%, var(--nizaam-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .divider {
            height: 1px;
            background: var(--bs-border-color);
            margin: 1.5rem 0;
        }

        /* ========================================
           FILE ATTACHMENT STYLES
        ======================================== */
        .attachment-upload-area {
            border: 2px dashed var(--nizaam-gray-300);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all var(--transition-base);
            cursor: pointer;
            background: var(--nizaam-gray-50);
        }

        .attachment-upload-area:hover {
            border-color: var(--nizaam-primary);
            background: var(--nizaam-primary-light);
        }

        .attachment-upload-area.drag-over {
            border-color: var(--nizaam-primary);
            background: var(--nizaam-primary-light);
            transform: scale(1.02);
        }

        .attachment-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid var(--bs-border-color);
            border-radius: 10px;
            background: var(--bs-body-bg);
            transition: all var(--transition-fast);
        }

        .attachment-item:hover {
            box-shadow: var(--shadow-sm);
            border-color: var(--nizaam-primary);
        }

        .attachment-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            background: var(--nizaam-gray-100);
            color: var(--nizaam-primary);
        }

        .attachment-info {
            flex-grow: 1;
            min-width: 0;
        }

        .attachment-name {
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.25rem;
        }

        .attachment-meta {
            font-size: 0.8rem;
            color: var(--nizaam-gray-600);
        }

        .attachment-actions {
            display: flex;
            gap: 0.5rem;
        }

        .attachment-preview {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 0.5rem;
        }

        /* Notification Panel Styles */
        .notification-dropdown .dropdown-toggle {
            color: var(--nizaam-text);
        }
        
        .notification-dropdown .dropdown-toggle:hover {
            color: var(--nizaam-primary);
        }
        
        .notification-panel {
            width: 380px;
            max-width: 90vw;
            max-height: 600px;
            border: none;
            padding: 0;
            margin-top: 0.5rem;
            background: white;
            border: 1px solid var(--bs-border-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        [data-bs-theme="dark"] .notification-panel {
            background: #1e293b;
            border-color: rgba(59, 130, 246, 0.2);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }
        
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--nizaam-border-color);
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #2563eb 100%);
            color: rgba(255, 255, 255, 0.9);
        }
        
        .notification-header h6 {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
        }
        
        .notification-header h6 i {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .notification-header .badge {
            background: rgba(255, 255, 255, 0.2) !important;
            color: white;
        }
        
        .notification-body {
            max-height: 420px;
            overflow-y: auto;
            padding: 0;
            background: white;
        }
        
        [data-bs-theme="dark"] .notification-body {
            background: #1e293b;
        }
        
        .notification-body::-webkit-scrollbar {
            width: 6px;
        }
        
        .notification-body::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .notification-body::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.3);
            border-radius: 3px;
        }
        
        .notification-body::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.5);
        }
        
        .notification-item {
            display: flex;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--nizaam-border-color);
            text-decoration: none;
            color: inherit;
            transition: var(--transition-base);
            position: relative;
            background: transparent;
        }
        
        [data-bs-theme="dark"] .notification-item {
            border-bottom-color: rgba(59, 130, 246, 0.15);
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-item:hover {
            background: rgba(59, 130, 246, 0.08);
        }
        
        [data-bs-theme="dark"] .notification-item:hover {
            background: rgba(59, 130, 246, 0.1);
        }
        
        .notification-item-unread {
            background: rgba(59, 130, 246, 0.08);
        }
        
        [data-bs-theme="dark"] .notification-item-unread {
            background: rgba(59, 130, 246, 0.12);
        }
        
        .notification-item-icon {
            flex-shrink: 0;
        }
        
        .notification-item-icon .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .icon-circle-primary { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .icon-circle-success { background: linear-gradient(135deg, #10b981, #059669); }
        .icon-circle-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .icon-circle-danger { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .icon-circle-info { background: linear-gradient(135deg, #06b6d4, #0891b2); }
        
        .notification-item-content {
            flex: 1;
            min-width: 0;
        }
        
        .notification-item-title {
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            color: var(--nizaam-text);
        }
        
        [data-bs-theme="dark"] .notification-item-title {
            color: rgba(255, 255, 255, 0.95);
        }
        
        .notification-item-message {
            font-size: 0.8125rem;
            color: var(--bs-secondary-color);
            margin-bottom: 0.25rem;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        [data-bs-theme="dark"] .notification-item-message {
            color: rgba(148, 163, 184, 0.85);
        }
        
        .notification-item-time {
            font-size: 0.75rem;
            color: var(--bs-tertiary-color);
        }
        
        [data-bs-theme="dark"] .notification-item-time {
            color: rgba(148, 163, 184, 0.7);
        }
        
        .notification-item-badge {
            flex-shrink: 0;
        }
        
        .notification-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.25rem;
            border-top: 1px solid var(--bs-border-color);
            background: #f8f9fa;
        }
        
        [data-bs-theme="dark"] .notification-footer {
            background: #1e293b;
            border-top-color: rgba(59, 130, 246, 0.2);
        }
        
        .notification-footer a,
        .notification-footer button {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--nizaam-primary);
        }
        
        [data-bs-theme="dark"] .notification-footer a,
        [data-bs-theme="dark"] .notification-footer button {
            color: #60a5fa;
        }
        
        .notification-footer button:hover {
            color: var(--nizaam-secondary);
        }
        
        [data-bs-theme="dark"] .notification-footer a:hover,
        [data-bs-theme="dark"] .notification-footer button:hover {
            color: #93c5fd;
        }
        
        .notification-empty {
            padding: 3rem 2rem;
            text-align: center;
            color: rgba(148, 163, 184, 0.8);
        }
        
        [data-bs-theme="dark"] .notification-empty {
            color: rgba(148, 163, 184, 0.7);
        }
        
        .notification-empty i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: rgba(148, 163, 184, 0.5);
        }
        
        [data-bs-theme="dark"] .notification-empty i {
            color: rgba(148, 163, 184, 0.4);
        }

        /* Responsive utilities */
        @media (max-width: 768px) {
            .topbar-title {
                font-size: 1.25rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .card-body {
                padding: 1rem;
            }
            
            .notification-panel {
                width: 320px;
            }
        }
    </style>
</head>
<body>
    <?php if (Session::has('user')): ?>
    <!-- Modern Sidebar with Nizaam Branding -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <a href="<?= $this->getBaseUrl() ?>/dashboard" class="brand-name">
                <div class="brand-logo">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>
                <span>NIZAAM</span>
            </a>
            <div class="brand-tagline">Company Operating System</div>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">Main</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'active' : '' ?>" href="<?= $this->getBaseUrl() ?>/dashboard">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                </li>
            </ul>

            <div class="nav-section">Work Management</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/work-items') !== false ? 'active' : '' ?>" href="<?= $this->getBaseUrl() ?>/work-items">
                        <i class="bi bi-kanban"></i> Work Items
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/projects') !== false ? 'active' : '' ?>" href="<?= $this->getBaseUrl() ?>/projects">
                        <i class="bi bi-folder-fill"></i> Projects
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/leaves') !== false ? 'active' : '' ?>" href="<?= $this->getBaseUrl() ?>/leaves">
                        <i class="bi bi-calendar-event"></i> Leaves
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/timesheets') !== false ? 'active' : '' ?>" href="<?= $this->getBaseUrl() ?>/timesheets">
                        <i class="bi bi-clock-history"></i> Timesheets
                    </a>
                </li>
            </ul>

            <div class="nav-section">Team</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/employees') !== false ? 'active' : '' ?>" href="<?= $this->getBaseUrl() ?>/employees">
                        <i class="bi bi-people-fill"></i> Employees
                    </a>
                </li>
            </ul>

            <div class="nav-section">Financial</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->getBaseUrl() ?>/expenses/create">
                        <i class="bi bi-receipt"></i> Expenses
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->getBaseUrl() ?>/timesheets/create">
                        <i class="bi bi-clock-history"></i> Timesheets
                    </a>
                </li>
            </ul>

            <?php if ($this->isAdmin()): ?>
            <div class="nav-section">Administration</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/reports') !== false ? 'active' : '' ?>" href="<?= $this->getBaseUrl() ?>/reports">
                        <i class="bi bi-graph-up"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/audit') !== false ? 'active' : '' ?>" href="<?= $this->getBaseUrl() ?>/audit">
                        <i class="bi bi-shield-check"></i> Audit Log
                    </a>
                </li>
            </ul>
            <?php endif; ?>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content page-transition">
        <!-- Modern Topbar with Actions -->
        <div class="topbar">
            <div class="topbar-title">
                <i class="bi bi-grid-1x2-fill"></i>
                <?= $title ?? 'Dashboard' ?>
            </div>
            <div class="topbar-actions">
                <!-- Mobile Menu Toggle -->
                <button class="btn btn-link d-md-none" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                
                <!-- Theme Toggle -->
                <button class="btn btn-link" id="themeToggle" title="Toggle theme (Auto/Light/Dark)">
                    <i class="bi bi-sun-fill fs-5" id="theme-icon-light"></i>
                    <i class="bi bi-moon-stars-fill fs-5 d-none" id="theme-icon-dark"></i>
                    <i class="bi bi-circle-half fs-5 d-none" id="theme-icon-auto"></i>
                </button>
                
                <!-- Notifications Dropdown -->
                <div class="dropdown notification-dropdown">
                    <button class="btn btn-link notification-badge position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell fs-5"></i>
                        <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $unreadCount ?>
                        </span>
                        <?php endif; ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notification-panel shadow-lg" aria-labelledby="notificationDropdown">
                        <div class="notification-header">
                            <h6 class="mb-0">
                                <i class="bi bi-bell-fill text-primary"></i>
                                Notifications
                            </h6>
                            <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                            <span class="badge bg-primary"><?= $unreadCount ?> New</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="notification-body">
                            <?php
                            $notificationModel = new Notification();
                            $employee = Session::get('employee');
                            $recentNotifications = $employee ? $notificationModel->getForEmployee($employee['id'], 5) : [];
                            ?>
                            
                            <?php if (empty($recentNotifications)): ?>
                            <div class="text-center py-4">
                                <i class="bi bi-bell-slash fs-1 text-muted opacity-25"></i>
                                <p class="text-muted small mb-0 mt-2">No notifications</p>
                            </div>
                            <?php else: ?>
                            <?php foreach ($recentNotifications as $notif): ?>
                            <a href="<?= $notif['link'] ?? ($this->getBaseUrl() . '/notifications') ?>" 
                               class="notification-item <?= $notif['is_read'] ? '' : 'notification-item-unread' ?>"
                               onclick="markNotificationRead(<?= $notif['id'] ?>)">
                                <div class="notification-item-icon">
                                    <?php
                                    $iconClass = 'bi-bell';
                                    $iconColor = 'var(--nizaam-primary)';
                                    
                                    if (strpos($notif['type'], 'work_item') !== false) {
                                        $iconClass = 'bi-list-task';
                                        $iconColor = 'var(--nizaam-info)';
                                    } elseif (strpos($notif['type'], 'comment') !== false) {
                                        $iconClass = 'bi-chat-dots';
                                        $iconColor = 'var(--nizaam-success)';
                                    } elseif (strpos($notif['type'], 'project') !== false) {
                                        $iconClass = 'bi-folder';
                                        $iconColor = 'var(--nizaam-secondary)';
                                    }
                                    ?>
                                    <div class="icon-circle" style="background: <?= $iconColor ?>;">
                                        <i class="bi <?= $iconClass ?>"></i>
                                    </div>
                                </div>
                                <div class="notification-item-content">
                                    <div class="notification-item-title"><?= htmlspecialchars($notif['title']) ?></div>
                                    <div class="notification-item-message"><?= htmlspecialchars($notif['message']) ?></div>
                                    <div class="notification-item-time">
                                        <?php
                                        $timestamp = strtotime($notif['created_at']);
                                        $now = time();
                                        $diff = $now - $timestamp;
                                        
                                        if ($diff < 60) {
                                            echo 'Just now';
                                        } elseif ($diff < 3600) {
                                            echo floor($diff / 60) . ' min ago';
                                        } elseif ($diff < 86400) {
                                            echo floor($diff / 3600) . 'h ago';
                                        } else {
                                            echo floor($diff / 86400) . 'd ago';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php if (!$notif['is_read']): ?>
                                <div class="notification-item-badge">
                                    <span class="badge bg-primary rounded-pill">●</span>
                                </div>
                                <?php endif; ?>
                            </a>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="notification-footer">
                            <a href="<?= $this->getBaseUrl() ?>/notifications" class="text-decoration-none">
                                View All Notifications
                            </a>
                            <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                            <form action="<?= $this->getBaseUrl() ?>/notifications/read-all" method="POST" style="display: inline;" onsubmit="return markAllRead()">
                                <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0">
                                    Mark All Read
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle user-profile p-0 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            <?php $empName = Session::get('employee')['full_name'] ?? 'User'; ?>
                            <?= strtoupper(substr($empName, 0, 1)) ?>
                        </div>
                        <div class="d-none d-lg-flex flex-column align-items-start text-start">
                            <span style="line-height: 1.2; font-weight: 600;"><?= htmlspecialchars($empName) ?></span>
                            <small style="font-size: 0.75rem; opacity: 0.7; font-weight: 400;"><?= htmlspecialchars(Session::get('employee')['designation'] ?? 'Employee') ?></small>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <?php if (Session::get('employee')): ?>
                        <li class="px-3 py-2 border-bottom" style="border-color: var(--bs-border-color) !important;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar" style="width: 48px; height: 48px; font-size: 1.25rem;">
                                    <?= strtoupper(substr($empName, 0, 1)) ?>
                                </div>
                                <div>
                                    <div style="font-weight: 600; font-size: 0.9375rem;"><?= htmlspecialchars($empName) ?></div>
                                    <div style="font-size: 0.8125rem; opacity: 0.7;"><?= htmlspecialchars(Session::get('employee')['email'] ?? '') ?></div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= $this->getBaseUrl() ?>/employees/<?= Session::get('employee')['id'] ?>">
                                <i class="bi bi-person-circle"></i> My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= $this->getBaseUrl() ?>/settings">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>
                        <li>
                            <a class="dropdown-item text-danger" href="<?= $this->getBaseUrl() ?>/logout">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Area with Flash Messages -->
        <div class="content-area">
            <?php if ($error = Session::flash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-down">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if ($success = Session::flash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Success:</strong> <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if ($info = Session::flash('info')): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert" data-aos="fade-down">
                <i class="bi bi-info-circle-fill me-2"></i>
                <?= htmlspecialchars($info) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if ($warning = Session::flash('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert" data-aos="fade-down">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <strong>Warning:</strong> <?= htmlspecialchars($warning) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
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
    <script>
        // Dark mode implementation with OS default support
        const getStoredTheme = () => localStorage.getItem('theme');
        const setStoredTheme = theme => localStorage.setItem('theme', theme);
        const getPreferredTheme = () => {
            const storedTheme = getStoredTheme();
            
            <!-- Page content will be inserted here -->
            <?php if (isset($content)) echo $content; ?>
        </div>
    </div>
    
    <?php else: ?>
    <!-- Guest layout (Login page) -->
    <div class="content-area" style="margin-left: 0;">
        <?php if (isset($content)) echo $content; ?>
    </div>
    <?php endif; ?>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // ============================================
        // THEME MANAGEMENT - Dark Mode with OS Detection
        // ============================================
        const getStoredTheme = () => localStorage.getItem('nizaam-theme');
        const setStoredTheme = theme => localStorage.setItem('nizaam-theme', theme);
        const getPreferredTheme = () => {
            const storedTheme = getStoredTheme();
            if (storedTheme) {
                return storedTheme;
            }
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        };

        const setTheme = theme => {
            if (theme === 'auto') {
                document.documentElement.setAttribute('data-bs-theme', 
                    window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme);
            }
            updateThemeIcon(theme);
        };

        const updateThemeIcon = theme => {
            const lightIcon = document.getElementById('theme-icon-light');
            const darkIcon = document.getElementById('theme-icon-dark');
            const autoIcon = document.getElementById('theme-icon-auto');
            
            if (lightIcon && darkIcon && autoIcon) {
                lightIcon.classList.add('d-none');
                darkIcon.classList.add('d-none');
                autoIcon.classList.add('d-none');
                
                if (theme === 'light') {
                    lightIcon.classList.remove('d-none');
                } else if (theme === 'dark') {
                    darkIcon.classList.remove('d-none');
                } else {
                    autoIcon.classList.remove('d-none');
                }
            }
        };

        // Initialize theme
        const currentTheme = getStoredTheme() || 'auto';
        setTheme(currentTheme);

        // Listen for OS theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            const storedTheme = getStoredTheme();
            if (storedTheme !== 'light' && storedTheme !== 'dark') {
                setTheme(getPreferredTheme());
            }
        });

        // Theme toggle button
        document.getElementById('themeToggle')?.addEventListener('click', () => {
            const currentTheme = getStoredTheme() || 'auto';
            let newTheme;
            
            if (currentTheme === 'auto') {
                newTheme = 'light';
            } else if (currentTheme === 'light') {
                newTheme = 'dark';
            } else {
                newTheme = 'auto';
            }
            
            setStoredTheme(newTheme);
            setTheme(newTheme);
        });

        // ============================================
        // MOBILE SIDEBAR TOGGLE
        // ============================================
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && sidebar && toggle) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // ============================================
        // INITIALIZE AOS ANIMATIONS
        // ============================================
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });

        // ============================================
        // DATATABLE INITIALIZATION (Global)
        // ============================================
        $(document).ready(function() {
            // Initialize all tables with class 'data-table'
            if ($.fn.DataTable) {
                $('.data-table').DataTable({
                    responsive: true,
                    pageLength: 25,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search records...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No entries available",
                        infoFiltered: "(filtered from _MAX_ total entries)",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "Next",
                            previous: "Previous"
                        }
                    },
                    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                         '<"row"<"col-sm-12"tr>>' +
                         '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    order: [[0, 'desc']]
                });
            }
        });

        // ============================================
        // RICH TEXT EDITOR INITIALIZATION (Global)
        // ============================================
        // Register Image Resize Module
        if (typeof Quill !== 'undefined' && typeof ImageResize !== 'undefined') {
            Quill.register('modules/imageResize', ImageResize.default);
        }
        
        function initQuillEditors() {
            document.querySelectorAll('.rich-editor').forEach(function(editorDiv) {
                if (editorDiv.classList.contains('ql-container')) {
                    return; // Already initialized
                }
                
                // Get the textarea BEFORE Quill transforms the div
                const textarea = editorDiv.previousElementSibling;
                if (!textarea || textarea.tagName !== 'TEXTAREA') {
                    return;
                }
                
                const quill = new Quill(editorDiv, {
                    theme: 'snow',
                    modules: {
                        toolbar: {
                            container: [
                                [{ 'header': [1, 2, 3, false] }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{ 'color': [] }, { 'background': [] }],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                [{ 'align': [] }],
                                ['link', 'image'],
                                ['clean']
                            ]
                        },
                        imageResize: {
                            displaySize: true,
                            modules: ['Resize', 'DisplaySize']
                        }
                    },
                    placeholder: editorDiv.getAttribute('data-placeholder') || 'Start typing...'
                });

                // Enable image paste from clipboard
                quill.root.addEventListener('paste', function(e) {
                    const clipboardData = e.clipboardData || window.clipboardData;
                    if (clipboardData && clipboardData.items) {
                        const items = clipboardData.items;
                        for (let i = 0; i < items.length; i++) {
                            if (items[i].type.indexOf('image') !== -1) {
                                e.preventDefault();
                                const file = items[i].getAsFile();
                                const reader = new FileReader();
                                reader.onload = function(event) {
                                    const range = quill.getSelection();
                                    quill.insertEmbed(range ? range.index : 0, 'image', event.target.result);
                                };
                                reader.readAsDataURL(file);
                            }
                        }
                    }
                });

                // Hide the textarea
                textarea.style.display = 'none';
                
                // Load initial value (HTML content from database)
                if (textarea.value && textarea.value.trim()) {
                    // Use innerHTML directly instead of clipboard conversion
                    // This preserves the exact HTML structure including images
                    quill.root.innerHTML = textarea.value;
                }
                
                // Sync on every change
                quill.on('text-change', function() {
                    textarea.value = quill.root.innerHTML;
                });
                
                // Sync before form submission and validate
                const form = textarea.closest('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        // Always sync content first
                        textarea.value = quill.root.innerHTML;
                        
                        // Custom validation for required rich text fields
                        const text = quill.getText().trim();
                        if (textarea.dataset.required === 'true' && text.length === 0) {
                            e.preventDefault();
                            e.stopPropagation();
                            editorDiv.style.border = '2px solid #dc3545';
                            editorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            
                            // Show error message
                            let errorMsg = editorDiv.parentElement.querySelector('.description-error');
                            if (!errorMsg) {
                                errorMsg = document.createElement('div');
                                errorMsg.className = 'description-error text-danger small mt-1';
                                errorMsg.textContent = 'This field is required';
                                editorDiv.parentElement.appendChild(errorMsg);
                            }
                            return false;
                        } else {
                            editorDiv.style.border = '';
                            const errorMsg = editorDiv.parentElement.querySelector('.description-error');
                            if (errorMsg) {
                                errorMsg.remove();
                            }
                        }
                    });
                }
            });
        }

        // Initialize on page load
        if (typeof Quill !== 'undefined') {
            // Wait for DOM to be fully ready and modules to load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(initQuillEditors, 150);
                });
            } else {
                setTimeout(initQuillEditors, 150);
            }
        }

        // ============================================
        // FILE ATTACHMENT HANDLING
        // ============================================
        function initFileUpload() {
            const uploadAreas = document.querySelectorAll('.attachment-upload-area');
            
            uploadAreas.forEach(area => {
                const input = area.querySelector('input[type="file"]');
                const fileList = area.nextElementSibling;
                
                if (!input) return;
                
                // Click to upload
                area.addEventListener('click', (e) => {
                    if (e.target !== input) {
                        input.click();
                    }
                });
                
                // Drag and drop
                area.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    area.classList.add('drag-over');
                });
                
                area.addEventListener('dragleave', () => {
                    area.classList.remove('drag-over');
                });
                
                area.addEventListener('drop', (e) => {
                    e.preventDefault();
                    area.classList.remove('drag-over');
                    const files = e.dataTransfer.files;
                    handleFiles(files, input, fileList);
                });
                
                // File selection
                input.addEventListener('change', (e) => {
                    handleFiles(e.target.files, input, fileList);
                });
            });
        }
        
        function handleFiles(files, input, fileList) {
            const dt = new DataTransfer();
            
            // Keep existing files
            if (input.files) {
                for (let i = 0; i < input.files.length; i++) {
                    dt.items.add(input.files[i]);
                }
            }
            
            // Add new files
            for (let i = 0; i < files.length; i++) {
                dt.items.add(files[i]);
            }
            
            input.files = dt.files;
            displayFiles(dt.files, fileList, input);
        }
        
        function displayFiles(files, fileList, input) {
            if (!fileList) return;
            
            fileList.innerHTML = '';
            
            Array.from(files).forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'attachment-item';
                fileItem.innerHTML = `
                    <div class="attachment-icon">
                        <i class="bi ${getFileIcon(file.type)}"></i>
                    </div>
                    <div class="attachment-info">
                        <div class="attachment-name">${file.name}</div>
                        <div class="attachment-meta">${formatFileSize(file.size)}</div>
                    </div>
                    <div class="attachment-actions">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index}, this)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                fileList.appendChild(fileItem);
            });
        }
        
        window.removeFile = function(index, button) {
            const fileList = button.closest('.attachment-list');
            const uploadArea = fileList.previousElementSibling;
            const input = uploadArea.querySelector('input[type="file"]');
            
            const dt = new DataTransfer();
            const files = Array.from(input.files);
            files.splice(index, 1);
            files.forEach(file => dt.items.add(file));
            input.files = dt.files;
            
            displayFiles(input.files, fileList, input);
        };
        
        function getFileIcon(type) {
            if (type.includes('image/')) return 'bi-file-image';
            if (type.includes('pdf')) return 'bi-file-pdf';
            if (type.includes('word') || type.includes('document')) return 'bi-file-word';
            if (type.includes('excel') || type.includes('spreadsheet')) return 'bi-file-excel';
            if (type.includes('zip') || type.includes('compressed')) return 'bi-file-zip';
            return 'bi-file-earmark';
        }
        
        function formatFileSize(bytes) {
            if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
            if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
            if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
            return bytes + ' bytes';
        }
        
        // Initialize file upload on page load
        document.addEventListener('DOMContentLoaded', initFileUpload);

        // ============================================
        // UTILITY FUNCTIONS
        // ============================================
        
        // Notification functions
        function markNotificationRead(notificationId) {
            fetch('<?= $this->getBaseUrl() ?>/notifications/' + notificationId + '/read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            }).catch(err => console.error('Error marking notification as read:', err));
        }
        
        function markAllRead() {
            return true; // Allow form to submit
        }
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Form validation feedback
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>
