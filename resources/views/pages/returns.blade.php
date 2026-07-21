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
        <span class="section-tag">Customer Support</span>
        <h1 class="page-hero-title">Returns & Exchanges</h1>
        <p class="page-hero-desc">We want you to love your purchase. If something's not right, we're here to help.</p>
    </div>
</section>

<section class="page-content">
    <div class="container container-narrow">
        <div class="content-block">
            <h2>Return Policy</h2>
            <p>We offer a <strong>14-day return policy</strong> from the date of delivery. If you're not completely satisfied with your purchase, you may return it for a refund or exchange, subject to the conditions below.</p>
        </div>

        <div class="content-block">
            <h2>Eligibility for Returns</h2>
            <p>To be eligible for a return, your item must be:</p>
            <ul class="check-list">
                <li>Unused and in the same condition you received it</li>
                <li>In its original packaging with all tags attached</li>
                <li>Accompanied by the original receipt or proof of purchase</li>
            </ul>
        </div>

        <div class="content-block">
            <h2>Non-Returnable Items</h2>
            <p>The following items cannot be returned or exchanged:</p>
            <ul class="check-list">
                <li>Custom-made or personalized furniture</li>
                <li>Items that have been assembled, modified, or damaged by the customer</li>
                <li>Items marked as "Final Sale" or "Clearance"</li>
                <li>Gift cards</li>
            </ul>
        </div>

        <div class="content-block">
            <h2>How to Initiate a Return</h2>
            <ol class="steps-list">
                <li>Contact our support team at <a href="mailto:hello@homei.com.bd">hello@homei.com.bd</a> or call <a href="tel:+8801XXXXXXXXX">+880 1XXX-XXXXXX</a> within 14 days of delivery.</li>
                <li>Provide your order number and reason for the return.</li>
                <li>Our team will review your request and provide return instructions.</li>
                <li>Pack the item securely in its original packaging.</li>
                <li>We'll arrange a pickup or provide drop-off instructions.</li>
            </ol>
        </div>

        <div class="content-block">
            <h2>Refunds</h2>
            <p>Once we receive and inspect your returned item, we'll process your refund within <strong>5–7 business days</strong>. Refunds will be credited to your original payment method:</p>
            <ul class="check-list">
                <li><strong>bKash / Nagad:</strong> Refunded to your mobile wallet</li>
                <li><strong>Card payments:</strong> Refunded to your card (may take 10–15 days depending on your bank)</li>
                <li><strong>Cash on Delivery:</strong> Refunded via bKash or bank transfer</li>
            </ul>
        </div>

        <div class="content-block">
            <h2>Exchanges</h2>
            <p>We're happy to exchange items for a different size, color, or product of equal value. Contact us to arrange an exchange, and we'll guide you through the process.</p>
        </div>

        <div class="content-block">
            <h2>Damaged or Defective Items</h2>
            <p>If your item arrives damaged or defective, please contact us within <strong>48 hours</strong> of delivery with photos of the damage. We'll arrange a free replacement or full refund — no questions asked.</p>
        </div>

        <div class="content-block">
            <h2>Need Help?</h2>
            <p>If you have any questions about returns or exchanges, our team is ready to assist:</p>
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
