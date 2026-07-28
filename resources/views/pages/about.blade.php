@extends('layouts.main')

@section('content')
@include('partials.header')

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
