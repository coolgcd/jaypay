 <nav class="navbar navbar-expand-lg fixed-top">
            <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#home" style="gap: -10px;">
    <img src="/assets/images/logo.png" alt="Logo" style="height: 80px; width: auto;">
    <span class="fw-bold fs-5">JAYPAY</span>
</a>
                <button
                    class="navbar-toggler border-0"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span
                        class="navbar-toggler-icon"
                        style="
                            background-image: url(&quot;data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAiIGhlaWdodD0iMzAiIHZpZXdCb3g9IjAgMCAzMCAzMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTQgN0gyNk00IDE1SDI2TTQgMjNIMjYiIHN0cm9rZT0iIzg3Q0VFQiIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+&quot;);
                        "
                    ></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
    <a class="nav-link" href="{{ route('front.home') }}">Home</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('front.about') }}">About Us</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('front.vision') }}">Vision & Mission</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('front.services') }}">Services</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('front.contactus') }}">Contact</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('front.legal') }}">Legal</a>
</li>
                        <li class="nav-item">
                           <a class="nav-link" href="{{ route('member.login') }}">Log in</a>

                        </li>
                    </ul>
                </div>
            </div>
        </nav>