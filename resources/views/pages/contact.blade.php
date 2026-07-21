@extends('layouts.main')

@section('content')
<header class="header" id="header">
    <div class="container header-inner">
        <a href="/" class="logo" id="logo">
            <span class="logo-text">HOMEI</span>
            <span class="logo-tagline">Cozy Living</span>
        </a>
        <nav class="nav" id="main-nav">
            <ul class="nav-list">
                <li><a href="/" class="nav-link">Home</a></li>
                <li class="has-dropdown">
                    <a href="/#categories" class="nav-link">Shop <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></a>
                    <div class="dropdown" id="shop-dropdown">
                        @foreach ($categories as $cat)
                        <a href="/category/{{ $cat->slug }}" class="dropdown-link">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                </li>
                <li><a href="/#new-arrivals" class="nav-link">New Arrivals</a></li>
                <li><a href="/#best-sellers" class="nav-link">Best Sellers</a></li>
            </ul>
        </nav>
        <div class="header-actions">
            <button class="icon-btn cart-btn" id="cart-btn" aria-label="Cart">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <span class="cart-count" id="cart-count">0</span>
            </button>
            @auth
            <a href="{{ auth()->user()->role && in_array(auth()->user()->role->name, ['super_admin', 'admin', 'manager']) ? '/admin/dashboard' : '#' }}" class="icon-btn user-btn" aria-label="Account">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>
            @else
            <a href="/login" class="icon-btn user-btn" aria-label="Login">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>
            @endauth
        </div>
    </div>
</header>

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
