@extends('layouts.main')

@section('content')
@include('partials.header')

<section class="page-hero">
    <div class="container">
        <span class="section-tag">Get in Touch</span>
        <h1 class="page-hero-title">Contact Us</h1>
        <p class="page-hero-desc">Have a question or need help? We're here for you.</p>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-form-wrap">
                <h2>Send Us a Message</h2>
                <form class="contact-form">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required placeholder="Your name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required placeholder="you@example.com">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="+880 1XXX-XXXXXX">
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <select id="subject" name="subject" required>
                            <option value="">Select a topic</option>
                            <option value="order">Order Inquiry</option>
                            <option value="returns">Returns & Exchanges</option>
                            <option value="product">Product Question</option>
                            <option value="delivery">Delivery Issue</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="5" required placeholder="How can we help you?"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                </form>
            </div>

            <div class="contact-info-wrap">
                <h2>Reach Us Directly</h2>
                <div class="contact-info-cards">
                    <div class="contact-info-card">
                        <div class="contact-info-icon">📍</div>
                        <h3>Visit Us</h3>
                        <p>Dhaka, Bangladesh</p>
                    </div>
                    <div class="contact-info-card">
                        <div class="contact-info-icon">📞</div>
                        <h3>Call Us</h3>
                        <p>+880 1XXX-XXXXXX</p>
                        <small>Sat–Thu, 10AM–8PM</small>
                    </div>
                    <div class="contact-info-card">
                        <div class="contact-info-icon">✉️</div>
                        <h3>Email Us</h3>
                        <p>hello@homei.com.bd</p>
                        <small>We reply within 24 hours</small>
                    </div>
                    <div class="contact-info-card">
                        <div class="contact-info-icon">💬</div>
                        <h3>WhatsApp</h3>
                        <p>+880 1XXX-XXXXXX</p>
                        <small>Quick responses</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')
@include('partials.cart-drawer')
@endsection
