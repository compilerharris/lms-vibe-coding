<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lead Management System')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <style>
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }
        body {
            background-color: #f8f9fa;
            font-size: 0.875rem; /* Reduced from default 1rem to 0.875rem (87.5%) */
        }
        
        /* Global size reduction */
        h1 { font-size: 1.5rem; }
        h2 { font-size: 1.25rem; }
        h3 { font-size: 1.125rem; }
        h4 { font-size: 1rem; }
        h5 { font-size: 0.875rem; }
        h6 { font-size: 0.75rem; }
        
        .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        
        /* Primary button border radius */
        .btn-primary {
            border-radius: 5px;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.6875rem;
        }
        
        .form-control {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        
        .card {
            margin-bottom: 0.75rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        .card-header {
            padding: 0.5rem 0.75rem;
        }
        
        .table {
            font-size: 0.75rem;
        }
        
        .table th, .table td {
            padding: 0.375rem;
        }
        
        /* Remove drop shadows from table rows */
        .table tr {
            box-shadow: none !important;
        }
        
        .table tbody tr {
            box-shadow: none !important;
        }
        
        .table tbody tr:hover {
            box-shadow: none !important;
        }
        
        /* Ensure DataTables rows have no shadow */
        #leadsTable tbody tr,
        #channelPartnersTable tbody tr,
        #projectsTable tbody tr,
        #usersTable tbody tr {
            box-shadow: none !important;
        }
        
        #leadsTable tbody tr:hover,
        #channelPartnersTable tbody tr:hover,
        #projectsTable tbody tr:hover,
        #usersTable tbody tr:hover {
            box-shadow: none !important;
        }
        .navbar {
            background: linear-gradient(135deg, #a136aa 0%, #000000 100%);
            padding: 0.375rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }
        
        .navbar-brand {
            font-size: 0.875rem;
        }
        
        .navbar-nav .nav-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        .card {
            border: none;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        .stat-card {
            background: linear-gradient(135deg, #a136aa 0%, #000000 100%);
            color: white;
        }
        .stat-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .stat-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card.info {
            background: linear-gradient(135deg, #a136aa 0%, #000000 100%);
        }
        
        /* Additional color variations */
        .text-primary {
            color: #a136aa !important;
        }
        
        .bg-primary {
            background-color: #a136aa !important;
        }
        
        .border-primary {
            border-color: #a136aa !important;
        }
        
        .btn-outline-primary {
            color: #a136aa;
            border-color: #a136aa;
        }
        
        .btn-outline-primary:hover {
            background-color: #a136aa;
            border-color: #a136aa;
        }
        .sidebar {
            background: white;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            padding: 0.75rem;
            position: fixed;
            top: 60px; /* Height of navbar */
            left: 15px;
            bottom: 15px;
            width: calc(16.666667% - 15px); /* col-md-2 width minus margin */
            overflow-y: auto;
            z-index: 1020;
        }
        
        /* Main content area adjustments for fixed sidebar */
        .main-content {
            margin-left: calc(16.666667%); /* col-md-2 width plus margin */
            margin-top: 60px; /* Height of navbar */
            padding: 15px;
        }
        
        /* Mobile responsive spacing */
        @media (max-width: 767.98px) {
            .sidebar {
                position: relative;
                top: auto;
                left: auto;
                bottom: auto;
                width: 100%;
                margin-bottom: 1.5rem;
                border-radius: 5px;
            }
            
            .main-content {
                margin-left: 0;
                margin-top: 0.75rem;
                padding: 0.5rem;
            }
            
            /* Ensure proper spacing on mobile */
            .row {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
            }
            
            .row > * {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }
        
        .sidebar .nav-link {
            color: #6c757d;
            border-radius: 5px;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(161, 54, 170, 0.1);
            color: #a136aa;
        }
        
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #a136aa 0%, #000000 100%);
            color: white;
        }
        .btn-primary {
            background: linear-gradient(135deg, #a136aa 0%, #000000 100%);
            border: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .table {
            background: white;
            border-radius: 5px;
            /* allow horizontal overflow to be handled by the responsive wrapper */
            overflow: visible;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        /* ensure tables can scroll horizontally within their container if needed */
        .table-responsive,
        .card-body {
            overflow-x: auto;
        }
        .form-control {
            border-radius: 5px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #a136aa;
            box-shadow: 0 0 0 0.2rem rgba(161, 54, 170, 0.25);
        }
        .alert {
            border-radius: 5px;
            border: none;
        }
        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }
        .alert-success {
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
            color: white;
        }
        .alert-warning {
            background: linear-gradient(135deg, #ffd43b 0%, #fab005 100%);
            color: #333;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Enhanced Theme Colors */
        .text-dark {
            color: #000000 !important;
        }
        
        .bg-dark {
            background-color: #000000 !important;
        }
        
        .border-dark {
            border-color: #000000 !important;
        }
        
        /* Enhanced gradients for better contrast */
        .gradient-primary {
            background: linear-gradient(135deg, #a136aa 0%, #000000 100%);
        }
        
        .gradient-primary-reverse {
            background: linear-gradient(135deg, #000000 0%, #a136aa 100%);
        }
        
        /* Enhanced card shadows with theme colors */
        .card-enhanced {
            box-shadow: 0 8px 25px rgba(161, 54, 170, 0.15);
            border: 1px solid rgba(161, 54, 170, 0.1);
        }
        
        /* Enhanced button hover effects */
        .btn-primary:hover {
            background: linear-gradient(135deg, #000000 0%, #a136aa 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(161, 54, 170, 0.3);
        }

        /* Override Bootstrap dropdown default left:0 */
        .dropdown-menu[data-bs-popper] {
            left: unset !important; /* remove the left property entirely */
        }
        /* Align the navbar dropdown to the right to avoid clipping */
        .navbar .dropdown-menu {
            right: 0;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="Lead Assignment System" style="height: 40px; width: auto; background: white; padding: 8px; border-radius: 5px;" class="me-2">
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Main Content -->
    <div class="container-fluid">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colvis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>
    @yield('scripts')
</body>
</html>