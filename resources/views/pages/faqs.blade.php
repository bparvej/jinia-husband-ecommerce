@extends('layouts.main')

@section('content')
@include('partials.header')

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
                    <p>Contact us within 14 days of delivery at <a href="mailto:hello@homei.com.bd">hello@homei.com.bd</a> or call <a href="tel:+8801787656135">+880 1787656135</a>. We'll guide you through the return process. Items must be unused and in original packaging. Refunds are processed within 5–7 business days.</p>
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
                        <li>📞 Phone: <a href="tel:+8801787656135">+880 1787656135</a> (Sat–Thu, 10AM–8PM)</li>
                        <li>✉️ Email: <a href="mailto:homeibd26@gmail.com">homeibd26@gmail.com</a></li>
                        <li>💬 WhatsApp: <a href="https://wa.me/8801787656135" target="_blank">+880 1787656135</a></li>
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
