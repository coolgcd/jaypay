   @extends('layouts.front')


@section('title', 'contact us')


@section('content')

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


        
        @endsection