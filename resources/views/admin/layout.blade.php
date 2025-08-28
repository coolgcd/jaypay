<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 60px;
            --topbar-height: 60px;
        }

        body {
            font-size: 14px;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: #343a40;
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
            border-radius: 0;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover {
            background: #495057;
            color: #fff;
        }

        .sidebar .nav-link.active {
            background: #007bff;
            color: #fff;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }

        .sidebar.collapsed .nav-link-text {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            padding: 12px 20px;
            text-align: center;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        /* Dropdown menu styles */
        .sidebar .collapse {
            background: #2c3034;
        }

        .sidebar .collapse .nav-link {
            padding: 8px 20px 8px 50px;
            font-size: 13px;
        }

        .sidebar.collapsed .collapse {
            display: none !important;
        }

        /* Logo Styles */
        .logo {
            padding: 15px 20px;
            border-bottom: 1px solid #495057;
            text-align: center;
        }

        .logo img {
            width: 100px;
  height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        .sidebar.collapsed .logo img {
            width: 30px;
            height: 30px;
        }

        /* Topbar Styles */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            z-index: 999;
            transition: all 0.3s ease;
        }

        .topbar.expanded {
            left: var(--sidebar-collapsed-width);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            min-height: calc(100vh - var(--topbar-height));
            padding: 20px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Footer */
        .footer {
            margin-left: var(--sidebar-width);
            background: #fff;
            border-top: 1px solid #dee2e6;
            padding: 15px 20px;
            transition: all 0.3s ease;
        }

        .footer.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .topbar {
                left: 0;
            }

            .main-content,
            .footer {
                margin-left: 0;
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .mobile-overlay.show {
                display: block;
            }
        }

        /* Toggle Button */
        .btn-toggle {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 18px;
            padding: 8px 12px;
        }

        .btn-toggle:hover {
            background: #f8f9fa;
            border-radius: 4px;
        }

        /* Profile Dropdown */
        .profile-img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        @include('admin.sidebar')
    </nav>

    <!-- Topbar -->
    <header class="topbar" id="topbar">
        @include('admin.topbar')
    </header>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer" id="footer">
        @include('admin.footer')
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const topbar = document.getElementById('topbar');
            const mainContent = document.getElementById('mainContent');
            const footer = document.getElementById('footer');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            function isMobile() {
                return window.innerWidth <= 768;
            }

            function toggleSidebar() {
                if (isMobile()) {
                    sidebar.classList.toggle('show');
                    mobileOverlay.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('collapsed');
                    topbar.classList.toggle('expanded');
                    mainContent.classList.toggle('expanded');
                    footer.classList.toggle('expanded');
                }
            }

            function closeMobileSidebar() {
                if (isMobile()) {
                    sidebar.classList.remove('show');
                    mobileOverlay.classList.remove('show');
                }
            }

            // Toggle button click
            if (toggleBtn) {
                toggleBtn.addEventListener('click', toggleSidebar);
            }

            // Mobile overlay click
            mobileOverlay.addEventListener('click', closeMobileSidebar);

            // Window resize handler
            window.addEventListener('resize', function() {
                if (!isMobile()) {
                    sidebar.classList.remove('show');
                    mobileOverlay.classList.remove('show');
                }
            });
        });

        // Member lookup functionality
        document.addEventListener('DOMContentLoaded', function() {
            const memberIdInput = document.getElementById('member_id_input');
            const memberNameDisplay = document.getElementById('member_name_display');
            
            if (memberIdInput && memberNameDisplay) {
                memberIdInput.addEventListener('keyup', function() {
                    const memberId = this.value;

                    if (memberId.length > 0) {
                        fetch(`/admin/member-name/${memberId}`)
                            .then(response => response.json())
                            .then(data => {
                                memberNameDisplay.value = data.success ? data.name : 'Not found';
                            })
                            .catch(err => {
                                console.error(err);
                                memberNameDisplay.value = 'Error';
                            });
                    } else {
                        memberNameDisplay.value = '';
                    }
                });
            }
        });
    </script>
</body>
</html>