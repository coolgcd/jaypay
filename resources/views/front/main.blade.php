<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>JAYPAY</title>
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"
            rel="stylesheet"
        />
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
            rel="stylesheet"
        />
 <link rel="stylesheet" href="{{ asset('css/front.css') }}">
        </head>
    <body>
        <!-- Header -->
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
                            <a class="nav-link" href="#home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#services">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#feedback">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#legal">Legal</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="{{ route('member.login') }}">Log in</a>

                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Slider -->
        <section id="home">
            <div
                id="heroCarousel"
                class="carousel slide hero-slider"
                data-bs-ride="carousel"
            >
                <div class="carousel-indicators">
                    <button
                        type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="0"
                        class="active"
                    ></button>
                    <button
                        type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="1"
                    ></button>
                    <button
                        type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="2"
                    ></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active" style="background-image: url('/assets/images/slider01.jpg'); background-size: cover; background-position: center;">

                        <div class="container">
                            <div class="hero-content">
                                <h1>Welcome to JAYPAY</h1>
                                <p>
                                  Best mobile recharge
                                    Unity Bill Payment Platform
                                </p>
                                <a href="#services" class="btn-custom"
                                    >Explore Our Services</a
                                >
                            </div>
                        </div>

                    </div>
                    <div class="carousel-item" style="background-image: url('/assets/images/slider2.jpg'); background-size: cover; background-position: center;">

                        <div class="container">
                            <div class="hero-content">
                                <h1>Innovation Driven</h1>
                                <p>
                                    Leading the future with cutting-edge
                                    technology
                                </p>
                                <a href="#about" class="btn-custom"
                                    >Learn More</a
                                >
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item" style="background-image: url('/assets/images/razvan-chisu-Ua-agENjmI4-unsplash.jpg'); background-size: cover; background-position: center;">

                        <div class="container">
                            <div class="hero-content">
                                <h1>Dreams</h1>
                                <p>
                                   "EMPOWERING DREAMS, TOGETHER WE RISE."
                                </p>
                                <a href="#feedback" class="btn-custom"
                                    >Get In Touch</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <button
                    class="carousel-control-prev"
                    type="button"
                    data-bs-target="#heroCarousel"
                    data-bs-slide="prev"
                >
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button
                    class="carousel-control-next"
                    type="button"
                    data-bs-target="#heroCarousel"
                    data-bs-slide="next"
                >
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </section>

        <!-- Services -->
        <section id="services" class="section-padding">
            <div class="container">
                <div class="section-title animate-on-scroll">
                    <h2>Our Services</h2>
                    <p>
                        Delivering comprehensive solutions tailored to your
                        needs
                    </p>
                </div>
                <div class="row g-3">
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card animate-on-scroll">
                            <div class="service-icon">
                                <i class="fa fa-television" ></i>
                            </div>
                            <h4>DTH Recharge</h4>
                            <p>
                               Recharge your DTH connection quickly and easily.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card animate-on-scroll">
                            <div class="service-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h4>Mobile Recharge</h4>
                            <p>
                                Recharge your mobile quickly and effortlessly with support for all major networks. Enjoy secure payments and instant connectivity anytime, anywhere.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card animate-on-scroll">
                            <div class="service-icon">
                                <i class="fa fa-car"></i>
                            </div>
                            <h4>FastTag Recharge</h4>
                            <p>
                          Top up your Fastag balance anytime, hassle-free.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card animate-on-scroll">
                            <div class="service-icon">
                                <i class="fa fa-bolt"></i>
                            </div>
                            <h4>Electricity Bill</h4>
                            <p>
                               Pay your electricity bills conveniently online.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card animate-on-scroll">
                            <div class="service-icon">
                                <i class="fa fa-fire"></i>
                            </div>
                            <h4>GAS Bill</h4>
                            <p>
                                Settle your gas bills with just a few clicks.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card animate-on-scroll">
                            <div class="service-icon">
                               <i class="   fa fa-fire-extinguisher"></i>
                            </div>
                            <h4>Piped Gas Bill</h4>
                            <p>
                                 Pay your piped gas bill quickly and securely.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About -->
        <section id="about" class="section-padding">
            <div class="container">
                <div class="section-title animate-on-scroll">
                    <h2>About Us</h2>
                    <p>Welcome to Jay Pay <br>Technology , Mirzapur, Uttar Pradesh</p>
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="about-text animate-on-scroll">
                            <h3>Building Tomorrow's Solutions Today</h3>
                            <p>
                               WE are team on the market that provides you
with online and digital services. From here you
can easily get good cashback by taking all
online services
We want to create a huge userbase through
Jay Pay where we can launch our 100+
projects in the coming few years.
                            </p> 

                            <p>MANAGING DIRECTOR<br>
MR. RAM ASARE PRAJAPATI<br>
MR. SHIVRAJ</p>
                            <!-- <a href="#feedback" class="btn-custom"
                                >Start Your Project</a
                            > -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-image animate-on-scroll">
                            <i class="fas fa-users fa-5x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Feedback Form -->
        <section id="feedback" class="section-padding">
            <div class="container">
                <div class="section-title animate-on-scroll">
                    <h2>Get In Touch</h2>
                    <p>We'd love to hear from you and discuss your project</p>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <form class="animate-on-scroll">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Your Name"
                                        required
                                    />
                                </div>
                                <div class="col-md-6">
                                    <input
                                        type="email"
                                        class="form-control"
                                        placeholder="Your Email"
                                        required
                                    />
                                </div>
                                <div class="col-md-6">
                                    <input
                                        type="tel"
                                        class="form-control"
                                        placeholder="Phone Number"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" required>
                                        <option value="">Select Service</option>
                                        <option value="web">
                                            Web Development
                                        </option>
                                        <option value="mobile">
                                            Mobile Apps
                                        </option>
                                        <option value="marketing">
                                            Digital Marketing
                                        </option>
                                        <option value="security">
                                            Cybersecurity
                                        </option>
                                        <option value="cloud">
                                            Cloud Solutions
                                        </option>
                                        <option value="support">Support</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <textarea
                                        class="form-control"
                                        rows="5"
                                        placeholder="Tell us about your project..."
                                        required
                                    ></textarea>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn-custom">
                                        Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Legal -->
        <section id="legal" class="section-padding">
    <div class="container">
        <div class="section-title animate-on-scroll">
            <h2>Legal Documents</h2>
            <p>Verified and certified legal documents for transparency and compliance</p>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="legal-card animate-on-scroll">
                    <h4><i class="fas fa-file-alt"></i> SPICE + Part B Approval Letter</h4>
                    <a href="/storage/legal/SPICE%20+%20Part%20B_Approval%20Letter_AA4872620.pdf" target="_blank">View Document (PDF)</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="legal-card animate-on-scroll">
                    <h4><i class="fas fa-id-card"></i> PAN Card</h4>
                    <a href="/storage/legal/jay%20ho%20pan.pdf" target="_blank">View Document (PDF)</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="legal-card animate-on-scroll">
                    <h4><i class="fas fa-gavel"></i> Memorandum of Association (EMoA)</h4>
                    <a href="/storage/legal/emoa-final.pdf" target="_blank">View Document (PDF)</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="legal-card animate-on-scroll">
                    <h4><i class="fas fa-scale-balanced"></i> Articles of Association (EAoA)</h4>
                    <a href="/storage/legal/eaoa-final.pdf" target="_blank">View Document (PDF)</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="legal-card animate-on-scroll">
                    <h4><i class="fas fa-certificate"></i> Company Incorporation Certificate</h4>
                    <a href="/storage/legal/AA091024021993B_RC05102024.pdf" target="_blank">View Document (PDF)</a>
                </div>
            </div>
            <!-- Add one more if you have a 6th doc -->
        </div>
    </div>
</section>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                         <a class="navbar-brand d-flex align-items-center" href="#home" style="gap: -10px;">
    <img src="/assets/images/logo.png" alt="Logo" style="height: 80px; width: auto;">
    <span class="fw-bold fs-5">JAYPAY</span>
</a>

                        <p>
                            Your trusted partner for innovative digital
                            solutions. We transform ideas into reality with
                            cutting-edge technology and exceptional service.
                        </p>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <h5>Services</h5>
                        <ul class="footer-links">
                            <li><a href="#services">DTH Recharge</a></li>
                            <li><a href="#services">Mobile Recharge</a></li>
                            <li><a href="#services">FastTag Recharge</a></li>
                            <li><a href="#services">Gas Bill</a></li>
                            <li><a href="#services">Piped Gas Bill</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <h5>Company</h5>
                        <ul class="footer-links">
                            <li><a href="#about">About Us</a></li>
                            <li><a href="#feedback">Contact</a></li>
                            <li><a href="#legal">Legal</a></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Blog</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <h5>Contact Info</h5>
                        <p>
                            <i class="fas fa-map-marker-alt"></i> 123 Business
                            Street, City, State 12345
                        </p>
                        <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                        <p>
                            <i class="fas fa-envelope"></i> info@jaypay.com
                        </p>
                    
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>
                        &copy; 2025 JayPay. All rights reserved. Designed
                        with
                        <i class="fas fa-heart" style="color: #87ceeb"></i> for
                        excellence.
                    </p>
                </div>
            </div>
        </footer>

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