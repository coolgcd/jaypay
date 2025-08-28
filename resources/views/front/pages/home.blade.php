@extends('layouts.front')


@section('title', 'Home')


@section('content')
<!-- <style>
    .ticker-overlay {
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 999;
        background: #000;
        color: #fff;
        white-space: nowrap;
        padding: 6px 0;
        overflow: hidden;
        font-size: 15px;
        border-bottom: 1px solid #333;
        margin-top: 120px;
    }

    .live-ticker-track {
        display: inline-block;
        animation: ticker-scroll 30s linear infinite;
    }

    .live-ticker-item {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin: 0 32px;
        color: #0f0;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
        margin-top: 10px;
    }

    .live-ticker-item:hover {
        color: #fff;
        text-decoration: underline;
    }

    .price-badge {
        margin-left: 6px;
        font-size: 13px;
        font-weight: bold;
    }


    @keyframes ticker-scroll {
        0% {
            transform: translateX(100%);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    @media (max-width: 768px) {
        .ticker-overlay {
            font-size: 13px;
            padding: 4px 0;
        }

        .live-ticker-item {
            margin: 0 16px;
        }

        .price-badge {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .ticker-overlay {
            font-size: 12px;
            padding: 3px 0;
        }

        .live-ticker-item {
            margin: 0 12px;
        }

        .price-badge {
            font-size: 11px;
        }
    }
</style> -->
<style>
    .custom-ticker-overlay {
        width: 100%;
        background: #111;
        color: #fff;
        white-space: nowrap;
        padding: 6px 0;
        overflow: hidden;
        font-size: 15px;
        border-bottom: 1px solid #333;
        margin-top: 120px;
    }

    .custom-ticker-track {
        display: inline-block;
        animation: ticker-scroll 30s linear infinite;
    }

    @keyframes ticker-scroll {
        0% {
            transform: translateX(100%);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    .ticker-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin: 0 24px;
        color: #0f0;
        font-weight: 600;
        text-decoration: none;
    }

    .ticker-item:hover {
        color: #fff;
    }

    .price-badge {
        font-size: 13px;
        font-weight: bold;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .custom-ticker-overlay {
            font-size: 13px;
            padding: 4px 0;
        }

        .ticker-item {
            margin: 0 16px;
        }
    }

    @media (max-width: 480px) {
        .custom-ticker-overlay {
            font-size: 12px;
            padding: 3px 0;
        }

        .ticker-item {
            margin: 0 12px;
        }
    }
</style>



<!-- Hero Slider -->
<section id="home">

    {{-- Ticker overlay on top of slider --}}
    <!-- <div class="ticker-overlay">

        <div class="custom-ticker-wrapper">

            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
                {
                    "symbols": [{
                            "proName": "FOREXCOM:NAS100",
                            "title": "NASDAQ 100"
                        },
                        {
                            "proName": "BITSTAMP:BTCUSD",
                            "title": "BTC/USD"
                        },
                        {
                            "proName": "BITSTAMP:ETHUSD",
                            "title": "ETH/USD"
                        },
                        {
                            "proName": "FOREXCOM:SPX500",
                            "title": "S&P 500"
                        },
                        {
                            "proName": "FOREXCOM:DJI",
                            "title": "Dow Jones"
                        },
                        {
                            "proName": "NSE:NIFTY",
                            "title": "NIFTY 50"
                        }
                    ],
                    "colorTheme": "dark", // or "light"
                    "isTransparent": false,
                    "displayMode": "adaptive",
                    "locale": "en"
                }
            </script>

        </div>

    </div> -->
    <div class="custom-ticker-overlay">
        <div class="custom-ticker-track" id="customTicker">
            <!-- Live ticker items will be injected here -->
            <script>
                const assets = [{
                        id: "bitcoin",
                        name: "Bitcoin",
                        icon: "fab fa-bitcoin",
                        link: "https://www.coingecko.com/en/coins/bitcoin"
                    },
                    {
                        id: "ethereum",
                        name: "Ethereum",
                        icon: "fab fa-ethereum",
                        link: "https://www.coingecko.com/en/coins/ethereum"
                    },
                    {
                        id: "tether",
                        name: "Tether",
                        icon: "fas fa-dollar-sign",
                        link: "https://www.coingecko.com/en/coins/tether"
                    },
                    {
                        id: "binancecoin",
                        name: "BNB",
                        icon: "fas fa-coins",
                        link: "https://www.coingecko.com/en/coins/binancecoin"
                    },
                    {
                        id: "usd-coin",
                        name: "USD Coin",
                        icon: "fas fa-dollar-sign",
                        link: "https://www.coingecko.com/en/coins/usd-coin"
                    },
                    {
                        id: "xrp",
                        name: "XRP",
                        icon: "fas fa-water",
                        link: "https://www.coingecko.com/en/coins/ripple"
                    },
                    {
                        id: "cardano",
                        name: "Cardano",
                        icon: "fas fa-gem",
                        link: "https://www.coingecko.com/en/coins/cardano"
                    },
                    {
                        id: "dogecoin",
                        name: "Dogecoin",
                        icon: "fas fa-dog",
                        link: "https://www.coingecko.com/en/coins/dogecoin"
                    },
                    {
                        id: "solana",
                        name: "Solana",
                        icon: "fas fa-sun",
                        link: "https://www.coingecko.com/en/coins/solana"
                    },
                    {
                        id: "tron",
                        name: "Tron",
                        icon: "fas fa-bolt",
                        link: "https://www.coingecko.com/en/coins/tron"
                    }
                ];


                async function updateTicker() {
                    try {
                        const res = await fetch("https://api.coingecko.com/api/v3/simple/price?ids=bitcoin,ethereum,tether,binancecoin,usd-coin,xrp,cardano,dogecoin,solana,tron&vs_currencies=usd");
                        const prices = await res.json();
                        const container = document.getElementById("customTicker");
                        container.innerHTML = "";

                        assets.forEach(asset => {
                            const price = prices[asset.id]?.usd ?? "N/A";
                            const html = `
        <a href="${asset.link}" class="ticker-item" target="_blank">
          <i class="${asset.icon}"></i>
          ${asset.name}
          <span class="price-badge">$${price.toLocaleString()}</span>
        </a>
      `;
                            container.insertAdjacentHTML("beforeend", html);
                        });
                    } catch (error) {
                        console.error("Failed to fetch ticker data", error);
                    }
                }

                // Initial load
                updateTicker();
                // Refresh every 30 sec
                setInterval(updateTicker, 30000);
            </script>
        </div>
    </div>






    <div
        id="heroCarousel"
        class="carousel slide hero-slider"
        data-bs-ride="carousel">

        <div class="carousel-indicators">
            <button
                type="button"
                data-bs-target="#heroCarousel"
                data-bs-slide-to="0"
                class="active"></button>
            <button
                type="button"
                data-bs-target="#heroCarousel"
                data-bs-slide-to="1"></button>
            <button
                type="button"
                data-bs-target="#heroCarousel"
                data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('/assets/images/stocks1.jpg'); background-size: cover; background-position: center;">

                <div class="container">
                    <div class="hero-content">
                        <h1>Welcome to JAYPAY</h1>
                        <p>
                            Best mobile recharge
                            Unity Bill Payment Platform
                        </p>
                        <a href="{{ asset('storage/JAYPAY-post.pdf') }}" target="_blank" class="btn-custom">
                            Explore Our Services
                        </a>

                    </div>
                </div>

            </div>
            <div class="carousel-item" style="background-image: url('/assets/images/stocks2.jpg'); background-size: cover; background-position: center;">

                <div class="container">
                    <div class="hero-content">
                        <h1>Innovation Driven</h1>
                        <p>
                            Leading the future with cutting-edge
                            technology
                        </p>
                        <a href="{{ asset('storage/JAYPAY-post.pdf') }}" target="_blank" class="btn-custom">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('/assets/images/stocks3.jpg'); background-size: cover; background-position: center;">

                <div class="container">
                    <div class="hero-content">
                        <h1>Dreams</h1>
                        <p>
                            "EMPOWERING DREAMS, TOGETHER WE RISE."
                        </p>
                        <a href="{{ asset('storage/JAYPAY-post.pdf') }}" target="_blank" class="btn-custom">
                            Join Us Today
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <button
            class="carousel-control-prev"
            type="button"
            data-bs-target="#heroCarousel"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button
            class="carousel-control-next"
            type="button"
            data-bs-target="#heroCarousel"
            data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

</section>

<section id="about" class="section-padding">
    <div class="container">
        <div class="section-title animate-on-scroll">
            <h2>About Us</h2>
            <p>Welcome to Jay Pay <br>Technology, Mirzapur, Uttar Pradesh</p>
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

<section id="dreams" class="section-padding">
    <div class="container">
        <div class="section-title animate-on-scroll text-center">
            <h2>Our Dreams</h2>
            <p>ELEVATE YOUR LIFESTYLE WITH TIMELESS LUXURY LIVING.</p>
        </div>
        <div class="row text-center dream-grid animate-on-scroll">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="dream-item p-4">
                    <i class="fas fa-clock fa-3x mb-3"></i>
                    <h5>Time Freedom</h5>
                    <p>"Embrace freedom's breeze, savor life's ease."</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="dream-item p-4">
                    <i class="fas fa-car-side fa-3x mb-3"></i>
                    <h5>Luxury Car</h5>
                    <p>"Elevate your drive to pure elegance."</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="dream-item p-4">
                    <i class="fas fa-home fa-3x mb-3"></i>
                    <h5>Luxury House</h5>
                    <p>"Elevate your lifestyle with timeless luxury living."</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="dream-item p-4">
                    <i class="fas fa-globe-asia fa-3x mb-3"></i>
                    <h5>World Trip</h5>
                    <p>"Explore the globe, live your dreams, one journey at a time."</p>
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
                                required />
                        </div>
                        <div class="col-md-6">
                            <input
                                type="email"
                                class="form-control"
                                placeholder="Your Email"
                                required />
                        </div>
                        <div class="col-md-6">
                            <input
                                type="tel"
                                class="form-control"
                                placeholder="Phone Number" />
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
                                required></textarea>
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



@endsection