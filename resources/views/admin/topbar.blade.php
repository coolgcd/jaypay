<div class="container-fluid h-100">
    <div class="row align-items-center h-100">
        <!-- Left side - Toggle button and search -->
        <div class="col-auto">
            <button class="btn btn-toggle" type="button" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Search bar (hidden on mobile) -->
       <div class="col d-none d-md-block">
    <form method="GET" action="{{ route('admin.members.index') }}">
        <div class="input-group" style="max-width: 400px;">
            <input 
                type="text" 
                name="member_id" 
                value="{{ request('member_id') }}" 
                class="form-control form-control-sm" 
                placeholder="Search by Member ID" 
                aria-label="Search"
            >
            <button class="btn btn-outline-secondary btn-sm" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>


        <!-- Right side - User dropdown and mobile search -->
        <div class="col-auto">
            <div class="d-flex align-items-center">
                <!-- Mobile search toggle -->
                <button class="btn btn-toggle d-md-none me-2" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-search"></i>
                </button>
                
                <!-- Mobile search dropdown -->
                <div class="dropdown-menu dropdown-menu-end p-3 d-md-none" style="width: 300px;">
                    <form method="GET" action="{{ route('admin.members.index') }}">
                        <div class="input-group">
                            <input 
                                type="text" 
                                name="member_id" 
                                value="{{ request('member_id') }}" 
                                class="form-control form-control-sm" 
                                placeholder="Search by Member ID" 
                                aria-label="Search"
                            >
                            <button class="btn btn-primary btn-sm" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/images/3.jpg') }}" alt="Profile" class="profile-img me-2">
                            <div class="d-none d-lg-block text-start">
                                <div class="fw-medium text-dark">{{ Auth::guard('admin')->user()->name }}</div>
                                <small class="text-muted">Administrator</small>
                            </div>
                            <i class="fas fa-chevron-down ms-2 text-muted"></i>
                        </div>
                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <h6 class="dropdown-header">
                                Welcome {{ Auth::guard('admin')->user()->name }}!
                            </h6>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#profile">
                                <i class="fas fa-user me-2"></i>
                                Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#settings">
                                <i class="fas fa-cog me-2"></i>
                                Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Hidden logout form -->
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Additional topbar styles */
    .btn-toggle:focus {
        box-shadow: none;
        outline: none;
    }

    .dropdown-toggle::after {
        display: none;
    }

    .profile-img {
        border: 2px solid #e9ecef;
        transition: border-color 0.2s ease;
    }

    .btn-link:hover .profile-img {
        border-color: #007bff;
    }

    .dropdown-menu {
        border: 1px solid #e9ecef;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        margin-top: 8px;
    }

    .dropdown-item {
        padding: 8px 16px;
        transition: background-color 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .dropdown-item.text-danger:hover {
        background-color: #fff5f5;
        color: #dc3545 !important;
    }

    .dropdown-header {
        color: #6c757d;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Search input focus */
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Mobile responsive adjustments */
    @media (max-width: 576px) {
        .profile-img {
            width: 30px;
            height: 30px;
        }
    }
</style>