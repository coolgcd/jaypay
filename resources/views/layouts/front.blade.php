<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'MNMT')</title>
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"
        rel="stylesheet" />
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/front.css') }}">
    @stack('styles')
</head>


<body>
  @include('front.partials.nav')

   <main>
        @yield('content')
    </main>

    @include('front.partials.footer')






   <!-- yield('scripts') {{-- For page-specific scripts --}} -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
        <script>
            // Wait for DOM to load
            document.addEventListener("DOMContentLoaded", function () {
                // Initialize Bootstrap carousel
                const carouselElement = document.querySelector("#heroCarousel");
                if (carouselElement) {
                    const carousel = new bootstrap.Carousel(carouselElement, {
                        interval: 4000,
                        wrap: true,
                        pause: "hover",
                    });
                }

                // Smooth scrolling for navigation links
                document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
                    anchor.addEventListener("click", function (e) {
                        e.preventDefault();
                        const target = document.querySelector(
                            this.getAttribute("href"),
                        );
                        if (target) {
                            target.scrollIntoView({
                                behavior: "smooth",
                                block: "start",
                            });
                        }
                    });
                });

                // Scroll animations
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: "0px 0px -50px 0px",
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("animated");
                        }
                    });
                }, observerOptions);

                document
                    .querySelectorAll(".animate-on-scroll")
                    .forEach((el) => {
                        observer.observe(el);
                    });

                // Active navigation
                window.addEventListener("scroll", () => {
                    const sections = document.querySelectorAll("section[id]");
                    const scrollPos = window.scrollY + 100;

                    sections.forEach((section) => {
                        const sectionTop = section.offsetTop;
                        const sectionHeight = section.offsetHeight;
                        const sectionId = section.getAttribute("id");

                        if (
                            scrollPos >= sectionTop &&
                            scrollPos < sectionTop + sectionHeight
                        ) {
                            document
                                .querySelectorAll(".navbar-nav .nav-link")
                                .forEach((link) => {
                                    link.classList.remove("active");
                                    if (
                                        link.getAttribute("href") ===
                                        `#${sectionId}`
                                    ) {
                                        link.classList.add("active");
                                    }
                                });
                        }
                    });
                });

                // Form submission
                const form = document.querySelector("form");
                if (form) {
                    form.addEventListener("submit", function (e) {
                        e.preventDefault();
                        alert(
                            "Thank you for your message! We will get back to you soon.",
                        );
                        this.reset();
                    });
                }
            });
        </script>
</body>

</html>