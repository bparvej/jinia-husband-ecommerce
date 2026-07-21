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
        <span class="section-tag">Help Center</span>
        <h1 class="page-hero-title">Frequently Asked Questions</h1>
        <p class="page-hero-desc">Find quick answers to common questions.</p>
    </div>
</section>

<section class="page-content">
    <div class="container container-narrow">
        <div class="faq-list" x-data="{ open: null }">

            <div class="faq-item">
                <button class="faq-question" @click="open = open === 1 ? null : 1">
                    <span>How do I place an order?</span>
                    <svg class="faq-chevron" :class="{ 'open': open === 1 }" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer" x-show="open === 1" x-collapse>
                    <p>Browse our products, add items to your cart, and proceed to checkout. Fill in your shipping details, choose a payment method (bKash, Nagad, Visa, Mastercard, or Cash on Delivery), and confirm your order. You'll receive an SMS/email confirmation immediately.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" @click="open = open === 2 ? null : 2">
                    <span>Do I need to create an account to order?</span>
                    <svg class="faq-chevron" :class="{ 'open': open === 2 }" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer" x-show="open === 2" x-collapse>
                    <p>No, you can check out as a guest. Simply provide your shipping details and phone number. We'll create a guest account for you automatically so you can track your order.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" @click="open = open === 3 ? null : 3">
                    <span>How long does delivery take?</span>
                    <svg class="faq-chevron" :class="{ 'open': open === 3 }" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer" x-show="open === 3" x-collapse>
                    <p>Delivery within Dhaka takes 2–5 business days. For Chittagong and Sylhet, expect 3–7 business days. Other districts may take 5–10 business days. You'll receive tracking updates via SMS.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" @click="open = open === 4 ? null : 4">
                    <span>Is Cash on Delivery available?</span>
                    <svg class="faq-chevron" :class="{ 'open': open === 4 }" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer" x-show="open === 4" x-collapse>
                    <p>Yes! Cash on Delivery (COD) is available across Bangladesh. You can also pay via bKash, Nagad, Visa, or Mastercard for online payment.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" @click="open = open === 5 ? null : 5">
                    <span>How do I return or exchange an item?</span>
                    <svg class="faq-chevron" :class="{ 'open': open === 5 }" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer" x-show="open === 5" x-collapse>
                    <p>Contact us within 14 days of delivery at <a href="mailto:hello@homei.com.bd">hello@homei.com.bd</a> or call +880 1XXX-XXXXXX. We'll guide you through the return process. Items must be unused and in original packaging. Refunds are processed within 5–7 business days.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" @click="open = open === 6 ? null : 6">
                    <span>What if my item arrives damaged?</span>
                    <svg class="faq-chevron" :class="{ 'open': open === 6 }" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer" x-show="open === 6" x-collapse>
                    <p>Contact us within 48 hours of delivery with photos of the damage. We'll arrange a free replacement or full refund — no questions asked.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" @click="open = open === 7 ? null : 7">
                    <span>Do you offer assembly services?</span>
                    <svg class="faq-chevron" :class="{ 'open': open === 7 }" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer" x-show="open === 7" x-collapse>
                    <p>Some furniture items come pre-assembled. For items that require assembly, we include easy-to-follow instructions. For larger pieces, we offer optional assembly assistance in Dhaka — contact us for details.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" @click="open = open === 8 ? null : 8">
                    <span>Can I track my order?</span>
                    <svg class="faq-chevron" :class="{ 'open': open === 8 }" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer" x-show="open === 8" x-collapse>
                    <p>Yes! Once your order is dispatched, you'll receive an SMS with tracking details. You can also contact us anytime with your order number for status updates.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" @click="open = open === 9 ? null : 9">
                    <span>Do you deliver outside Dhaka?</span>
                    <svg class="faq-chevron" :class="{ 'open': open === 9 }" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer" x-show="open === 9" x-collapse>
                    <p>Yes, we deliver to all districts across Bangladesh. Delivery times vary by location — Dhaka (2–5 days), Chittagong/Sylhet (3–7 days), and other districts (5–10 business days).</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" @click="open = open === 10 ? null : 10">
                    <span>How do I contact customer support?</span>
                    <svg class="faq-chevron" :class="{ 'open': open === 10 }" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer" x-show="open === 10" x-collapse>
                    <p>You can reach us via:</p>
                    <ul>
                        <li>📞 Phone: +880 1XXX-XXXXXX (Sat–Thu, 10AM–8PM)</li>
                        <li>✉️ Email: <a href="mailto:hello@homei.com.bd">hello@homei.com.bd</a></li>
                        <li>💬 WhatsApp: +880 1XXX-XXXXXX</li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="faq-cta">
            <h3>Still have questions?</h3>
            <p>Can't find the answer you're looking for? Reach out to our friendly support team.</p>
            <a href="/contact" class="btn btn-primary btn-lg">Contact Us</a>
        </div>
    </div>
</section>

@include('partials.footer')
@include('partials.cart-drawer')
@endsection
