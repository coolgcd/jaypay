<!-- resources/views/layouts/member.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Member Portal')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
   
   <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #bdc3c7 0%, #2c3e50 80%);
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
            --text-light: #f8f9fa;
            --text-dark: #343a40;
            --bg-light: #ffffff;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.05);
            --transition-speed: 0.3s;
        }
        
        body {
            background-color: #f9fafb;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            color: var(--text-dark);
            overflow-x: hidden;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            /* background: #2c3e50; */
            /* background: #7879FF; */
                background: linear-gradient(135deg, #ef4136, #fbb040);
                filter: brightness(0.9) saturate(0.8);

            color: var(--text-light);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 1rem;
            transition: all var(--transition-speed) ease;
            box-shadow: var(--shadow-md);
            z-index: 1030;
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        /* Logo styling */
        .sidebar-logo {
            text-align: center;
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }
        
        .logo-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            transition: all var(--transition-speed) ease;
        }
        
        .sidebar.collapsed .logo-img {
            width: 40px;
            height: 40px;
        }
        
        .sidebar-header {
            padding: 0 1rem 1rem;
            text-align: center;
        }
        
        .sidebar-header-text {
            font-size: 1.1rem;
            font-weight: 500;
            margin: 0;
            transition: all var(--transition-speed) ease;
        }
        
        .sidebar.collapsed .sidebar-header-text {
            opacity: 0;
            transform: scale(0.8);
            height: 0;
            margin: 0;
            overflow: hidden;
        }
        
        .sidebar-icon {
            font-size: 1.5rem;
            opacity: 0;
            transition: all var(--transition-speed) ease;
        }
        
        .sidebar.collapsed .sidebar-icon {
            opacity: 1;
        }
        
        /* Navigation styling */
        .sidebar-nav {
            padding: 0 0.5rem;
        }
        
        .nav-item {
            margin-bottom: 0.25rem;
        }
        
        .sidebar a, .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-radius: 0.375rem;
            transition: all var(--transition-speed) ease;
            white-space: nowrap;
        }
        
        .sidebar a:hover, 
        .sidebar a.active,
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
        
        .sidebar i {
            font-size: 1.1rem;
            width: 1.5rem;
            text-align: center;
            margin-right: 0.75rem;
            opacity: 0.9;
            flex-shrink: 0;
        }
        
        .text-label {
            transition: all var(--transition-speed) ease;
            overflow: hidden;
        }
        
        .sidebar.collapsed .text-label {
            opacity: 0;
            width: 0;
            margin: 0;
        }
        
        /* Main content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 1rem;
            transition: margin-left var(--transition-speed) ease;
            min-height: 100vh;
        }
        
        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        .navbar {
            background-color: var(--bg-light);
            box-shadow: var(--shadow-sm);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .navbar-brand {
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .toggle-sidebar {
            cursor: pointer;
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            color: var(--text-dark);
            transition: background-color 0.15s ease;
            border: 1px solid #dee2e6;
        }
        
        .toggle-sidebar:hover {
            background-color: rgba(0,0,0,0.05);
        }
        
        .content-wrapper {
            /* background-color: var(--bg-light); */
            background-color: #e7e4e2;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
        }
        
        /* Dropdown styling */
        .sidebar .dropdown-menu {
            position: absolute;
            left: 100%;
            top: 0;
            margin-left: 0.5rem;
            background-color: var(--bg-light);
            border-radius: 0.5rem;
            min-width: 200px;
            box-shadow: var(--shadow-md);
            border: none;
            padding: 0.5rem 0;
            z-index: 1031;
        }
        
        .sidebar .dropdown-menu .dropdown-item {
            padding: 0.6rem 1.25rem;
            font-size: 0.9rem;
            color: var(--text-dark);
            border-radius: 0;
            margin: 0;
            white-space: nowrap;
        }
        
        .sidebar .dropdown-menu .dropdown-item:hover {
            background-color: rgba(0,0,0,0.03);
        }
        
        .sidebar .dropdown-toggle::after {
            margin-left: auto;
            transition: all var(--transition-speed) ease;
        }
        
        .sidebar.collapsed .dropdown-toggle::after {
            opacity: 0;
        }
        
        /* Mobile responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .sidebar.collapsed {
                width: var(--sidebar-width);
                transform: translateX(-100%);
            }
            
            .sidebar.collapsed.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.expanded {
                margin-left: 0;
            }
            
            /* Mobile overlay */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                z-index: 1025;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                padding: 0.5rem;
            }
            
            .navbar {
                padding: 0.5rem 1rem;
                margin-bottom: 1rem;
            }
            
            .content-wrapper {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="sidebar-overlay d-lg-none" id="sidebar-overlay" onclick="closeSidebar()"></div>
    
    <!-- Sidebar -->
    @include('member.sidebar')

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-3 shadow-sm">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <button class="btn toggle-sidebar me-3" onclick="toggleSidebar()">
                        <i class="fa fa-bars"></i>
                    </button>
                    <span class="navbar-brand mb-0 h5">@yield('title', 'Dashboard')</span>
                </div>

                <div class="d-flex align-items-center">
                    <span class="me-3 text-dark fw-semibold d-none d-sm-inline">
                        👤 {{ Auth::guard('member')->user()->name ?? 'Member' }}
                    </span>
                </div>
            </div>
        </nav>

        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <script>
        // Initial setup
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            // Desktop: collapsed by default on smaller screens
            if (window.innerWidth >= 992 && window.innerWidth < 1200) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
            
            // Mobile: hide sidebar by default
            if (window.innerWidth < 992) {
                sidebar.classList.remove('show');
            }
            
            updateDropdownBehavior();
        });
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth < 992) {
                // Mobile behavior
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                // Desktop behavior
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
            
            updateDropdownBehavior();
        }
        
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }
        
        function updateDropdownBehavior() {
            const sidebar = document.getElementById('sidebar');
            const isSidebarCollapsed = sidebar.classList.contains('collapsed');
            const isMobile = window.innerWidth < 992;
            
            // Handle dropdowns for collapsed desktop sidebar
            const dropdowns = document.querySelectorAll('.sidebar .dropdown');
            dropdowns.forEach(dropdown => {
                const dropdownMenu = dropdown.querySelector('.dropdown-menu');
                
                if (isSidebarCollapsed && !isMobile) {
                    dropdown.addEventListener('mouseenter', function() {
                        dropdownMenu.classList.add('show');
                    });
                    
                    dropdown.addEventListener('mouseleave', function() {
                        dropdownMenu.classList.remove('show');
                    });
                } else {
                    // Remove hover events for mobile or expanded sidebar
                    dropdown.removeEventListener('mouseenter', function() {});
                    dropdown.removeEventListener('mouseleave', function() {});
                }
            });
        }
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth >= 992) {
                // Desktop: remove mobile classes
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            } else {
                // Mobile: reset to collapsed state
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            }
            
            updateDropdownBehavior();
        });
        
        // Close sidebar when clicking on links in mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 && e.target.closest('.sidebar a:not(.dropdown-toggle)')) {
                closeSidebar();
            }
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>