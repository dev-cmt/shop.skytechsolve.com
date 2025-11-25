{{-- resources/views/admin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Panel') - Page Builder</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .admin-sidebar {
            background: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            transition: all 0.3s;
            z-index: 1000;
        }

        .admin-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .sidebar-minimized .admin-sidebar {
            width: 70px;
        }

        .sidebar-minimized .admin-content {
            margin-left: 70px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            color: #adb5bd;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            color: white;
            background: #495057;
            border-left-color: #007bff;
        }

        .sidebar-menu .menu-icon {
            width: 30px;
            text-align: center;
        }

        .menu-text {
            transition: opacity 0.3s;
        }

        .sidebar-minimized .menu-text {
            opacity: 0;
            visibility: hidden;
        }

        .stat-card {
            border-radius: 10px;
            border: none;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .table-actions {
            white-space: nowrap;
        }

        .navbar-admin {
            background: #2c3034 !important;
            border-bottom: 1px solid #495057;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-admin navbar-dark">
        <div class="container-fluid">
            <button class="btn btn-dark" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <div class="d-flex">
                <span class="navbar-text me-3">
                    <i class="fas fa-user-circle me-2"></i>Welcome, {{ Auth::user()->name }}
                </span>
                <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm me-2" target="_blank">
                    <i class="fas fa-external-link-alt"></i> View Site
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid" id="adminWrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-header text-center py-4">
                <h4 class="sidebar-logo mb-0">
                    <i class="fas fa-cubes"></i>
                    <span class="menu-text">Page Builder</span>
                </h4>
                <small class="text-muted menu-text">Admin Panel</small>
            </div>

            <ul class="sidebar-menu mt-3">
                <li>
                    <a href="{{ route('admin.pages.index') }}" class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt menu-icon"></i>
                        <span class="menu-text">Pages</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag menu-icon"></i>
                        <span class="menu-text">Products</span>
                    </a>
                </li>

            </ul>
        </div>

        <!-- Main Content -->
        <main class="admin-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('adminWrapper').classList.toggle('sidebar-minimized');
        });

        // Auto-dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Initialize Select2
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
