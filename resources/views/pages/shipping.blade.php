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
        <span class="section-tag">Delivery Information</span>
        <h1 class="page-hero-title">Shipping Policy</h1>
        <p class="page-hero-desc">Fast, reliable delivery across Bangladesh.</p>
    </div>
</section>

<section class="page-content">
    <div class="container container-narrow">
        <div class="content-block">
            <h2>Delivery Coverage</h2>
            <p>We deliver to all addresses across Bangladesh, including Dhaka, Chittagong, Sylhet, Rajshahi, Khulna, Barisal, Rangpur, and Mymensingh divisions.</p>
        </div>

        <div class="content-block">
            <h2>Shipping Rates</h2>
            <div class="rate-table">
                <div class="rate-row rate-header">
                    <span>Order Value</span>
                    <span>Shipping Fee</span>
                </div>
                <div class="rate-row">
                    <span>Under ৳5,000</span>
                    <span>৳200 flat rate</span>
                </div>
                <div class="rate-row">
                    <span>৳5,000 and above</span>
                    <span class="text-success">FREE</span>
                </div>
            </div>
        </div>

        <div class="content-block">
            <h2>Delivery Timeline</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Dhaka City</h3>
                        <p>2–5 business days after order confirmation</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Chittagong & Sylhet</h3>
                        <p>3–7 business days after order confirmation</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Other Districts</h3>
                        <p>5–10 business days after order confirmation</p>
                    </div>
                </div>
            </div>
            <p class="note-text">* Business days exclude Fridays and public holidays. Delivery times may be affected during peak seasons or natural events.</p>
        </div>

        <div class="content-block">
            <h2>Order Processing</h2>
            <p>Orders are typically processed within <strong>1–2 business days</strong> after confirmation. You'll receive a notification once your order has been dispatched with tracking details.</p>
        </div>

        <div class="content-block">
            <h2>Delivery Process</h2>
            <ol class="steps-list">
                <li><strong>Order Confirmed:</strong> You'll receive an email/SMS confirmation.</li>
                <li><strong>Order Dispatched:</strong> Your item is packed and handed to our delivery partner.</li>
                <li><strong>In Transit:</strong> Track your order status via the link sent to your phone.</li>
                <li><strong>Out for Delivery:</strong> Our delivery agent will call you before arriving.</li>
                <li><strong>Delivered:</strong> Inspect your item upon receipt and confirm delivery.</li>
            </ol>
        </div>

        <div class="content-block">
            <h2>Payment Methods</h2>
            <p>We offer flexible payment options:</p>
            <ul class="check-list">
                <li><strong>Cash on Delivery (COD)</strong> — Pay when you receive your order</li>
                <li><strong>bKash</strong> — Mobile financial service</li>
                <li><strong>Nagad</strong> — Mobile financial service</li>
                <li><strong>Visa / Mastercard</strong> — Secure online payment</li>
            </ul>
        </div>

        <div class="content-block">
            <h2>Important Notes</h2>
            <ul class="check-list">
                <li>Please provide an accurate delivery address and contact number</li>
                <li>Our delivery agent will call you 30 minutes before arrival</li>
                <li>For large furniture items, please ensure someone is available to receive the delivery</li>
                <li>Inspect items before signing the delivery receipt</li>
            </ul>
        </div>

        <div class="content-block">
            <h2>Need Help?</h2>
            <div class="contact-quick">
                <p>📞 <a href="tel:+8801XXXXXXXXX">+880 1XXX-XXXXXX</a> (Sat–Thu, 10AM–8PM)</p>
                <p>✉️ <a href="mailto:hello@homei.com.bd">hello@homei.com.bd</a></p>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')
@include('partials.cart-drawer')
@endsection
