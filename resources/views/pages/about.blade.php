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
        <span class="section-tag">About Us</span>
        <h1 class="page-hero-title">Bringing Cozy Living<br>to Every Home</h1>
        <p class="page-hero-desc">Handcrafted wooden furniture and home décor designed for modern Bangladeshi living spaces.</p>
    </div>
</section>

<section class="page-content">
    <div class="container container-narrow">
        <div class="content-block">
            <h2>Who We Are</h2>
            <p>HomeI is a Bangladeshi furniture and home décor brand dedicated to creating beautiful, functional pieces that transform houses into homes. Founded with a passion for quality craftsmanship and timeless design, we bring you handpicked collections that blend warmth with modern aesthetics.</p>
            <p>We believe every home deserves furniture that tells a story — pieces that are built to last, designed to inspire, and crafted with care. From solid wood bookshelves to elegant dining sets, every HomeI product is made with sustainable materials and attention to detail.</p>
        </div>

        <div class="content-block">
            <h2>Our Mission</h2>
            <p>To make quality, handcrafted furniture accessible to every Bangladeshi household. We aim to combine traditional woodworking techniques with contemporary design, creating pieces that are both beautiful and built to stand the test of time.</p>
        </div>

        <div class="content-block">
            <h2>Our Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">🪵</div>
                    <h3>Quality Craftsmanship</h3>
                    <p>Every piece is built from sustainably sourced wood by skilled artisans who take pride in their work.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">🏠</div>
                    <h3>Designed for Living</h3>
                    <p>We create furniture that fits real Bangladeshi homes — functional, beautiful, and space-conscious.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">🤝</div>
                    <h3>Customer First</h3>
                    <p>From browsing to delivery, we ensure a seamless experience with responsive support at every step.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">🌱</div>
                    <h3>Sustainability</h3>
                    <p>We use eco-friendly materials and responsible sourcing to minimize our environmental footprint.</p>
                </div>
            </div>
        </div>

        <div class="content-block">
            <h2>Why Choose HomeI?</h2>
            <ul class="check-list">
                <li>Handcrafted wooden furniture made in Bangladesh</li>
                <li>Premium materials with attention to every detail</li>
                <li>Fast delivery across Bangladesh</li>
                <li>14-day hassle-free return policy</li>
                <li>Secure payment via bKash, Nagad, Visa, Mastercard, and Cash on Delivery</li>
                <li>Dedicated customer support</li>
            </ul>
        </div>
    </div>
</section>

@include('partials.footer')
@include('partials.cart-drawer')
@endsection
