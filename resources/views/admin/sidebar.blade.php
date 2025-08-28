<!-- Logo Section -->
<div class="logo">
    <img src="{{ asset('assets/images/logo.jpeg') }}" alt="Logo">
</div>

<!-- Navigation Menu -->
<div class="mt-3">
    <ul class="nav flex-column">
        <!-- Manage Pin Section -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#managePinCollapse" role="button" aria-expanded="false">
                <i class="fas fa-thumbtack"></i>
                <span class="nav-link-text">Manage Pin</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="managePinCollapse">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.topuppin.create') }}" class="nav-link">
                            <i class="fas fa-plus"></i>
                            <span class="nav-link-text">Create Topup Pin</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.topuppin.used') }}" class="nav-link">
                            <i class="fas fa-check-circle"></i>
                            <span class="nav-link-text">Used Topup Pin</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.topuppin.unused') }}" class="nav-link">
                            <i class="fas fa-circle"></i>
                            <span class="nav-link-text">Unused Topup Pin</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Manage Member Section -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#manageMemberCollapse" role="button" aria-expanded="false">
                <i class="fas fa-users"></i>
                <span class="nav-link-text">Manage Member</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="manageMemberCollapse">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.members.byjoindate') }}" class="nav-link">
                            <i class="fas fa-calendar-plus"></i>
                            <span class="nav-link-text">All Members</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ route('admin.members.active') }}" class="nav-link">
                            <i class="fas fa-user-check"></i>
                            <span class="nav-link-text">Active Members</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.members.inactive') }}" class="nav-link">
                            <i class="fas fa-user-times"></i>
                            <span class="nav-link-text">Inactive Members</span>
                        </a>
                    </li>




                </ul>
            </div>
        </li>


        <!-- Member Payments Section -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#memberPaymentCollapse" role="button" aria-expanded="false">
                <i class="fas fa-credit-card"></i>
                <span class="nav-link-text">Member Payments</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="memberPaymentCollapse">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.payments.index') }}" class="nav-link">
                            <i class="fas fa-list"></i>
                            <span class="nav-link-text">Payment Requests</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Withdraw Section -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#withdrawCollapse" role="button" aria-expanded="false">
                <i class="fas fa-wallet"></i>
                <span class="nav-link-text">Withdrawals</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="withdrawCollapse">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.withdraw.index') }}" class="nav-link">
                            <i class="fas fa-list"></i>
                            <span class="nav-link-text">All Requests</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.recharge.index') }}" class="nav-link">
                <i class="fas fa-bolt"></i>
                <span class="nav-link-text">Recharge Logs</span>
            </a>
        </li>
        <li class="nav-item">
    <a href="{{ route('admin.payment.logs') }}" class="nav-link">
        <i class="fas fa-file-invoice-dollar"></i>
        <span class="nav-link-text">Payment Logs</span>
    </a>
</li>


       <!-- Income Processing -->
<li class="nav-item">
    <a href="{{ route('admin.paymentrefresh.index') }}" class="nav-link">
        <i class="fas fa-sync-alt"></i>
        <span class="nav-link-text">Trigger Income</span>
    </a>
</li>

<!-- Add this in the appropriate section of your admin sidebar -->
<li class="nav-item">
    <a href="{{ route('admin.special.offer.index') }}" class="nav-link">
        <i class="fas fa-gift"></i>
        <span class="nav-link-text">Monsoon Offer</span>
    </a>
</li>



        <!-- Income Reports Section -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#incomeReportsCollapse" role="button" aria-expanded="false">
                <i class="fas fa-chart-line"></i>
                <span class="nav-link-text">Income Reports</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="incomeReportsCollapse">
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="{{ route('admin.income.daily') }}" class="nav-link">Daily Income</a></li>
                    <li class="nav-item"><a href="{{ route('admin.income.direct') }}" class="nav-link">Direct Income</a></li>
                    <li class="nav-item"><a href="{{ route('admin.income.matching') }}" class="nav-link">Matching Income</a></li>
                    <li class="nav-item"><a href="{{ route('admin.income.salary') }}" class="nav-link">Salary Income</a></li>
                    <li class="nav-item"><a href="{{ route('admin.income.reward') }}" class="nav-link">Reward Income</a></li>
                </ul>
            </div>
        </li>

    </ul>
</div>

<style>
    /* Additional styles for better dropdown behavior */
    .nav-link[data-bs-toggle="collapse"] {
        position: relative;
    }

    .nav-link[data-bs-toggle="collapse"] .fa-chevron-down {
        transition: transform 0.2s ease;
        font-size: 12px;
    }

    .nav-link[data-bs-toggle="collapse"]:not(.collapsed) .fa-chevron-down {
        transform: rotate(-180deg);
    }

    /* Active link highlighting */
    .nav-link.active {
        background-color: #007bff !important;
        color: #fff !important;
    }

    /* Hover effects for collapsed sidebar */
    .sidebar.collapsed .nav-item:hover .collapse {
        position: absolute;
        left: 100%;
        top: 0;
        width: 200px;
        background: #2c3034;
        border-radius: 0 4px 4px 0;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        display: block !important;
        z-index: 1001;
    }

    /* Remove dropdown arrow in collapsed mode */
    .sidebar.collapsed .fa-chevron-down {
        display: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set active menu based on current URL
        const currentUrl = window.location.href;
        const navLinks = document.querySelectorAll('.nav-link[href]');

        navLinks.forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add('active');

                // If it's a submenu item, expand parent
                const parentCollapse = link.closest('.collapse');
                if (parentCollapse) {
                    parentCollapse.classList.add('show');
                    const parentToggle = document.querySelector(`[href="#${parentCollapse.id}"]`);
                    if (parentToggle) {
                        parentToggle.classList.remove('collapsed');
                    }
                }
            }
        });
    });
</script>