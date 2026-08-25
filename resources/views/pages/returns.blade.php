@extends('layouts.main')

@section('content')
@include('partials.header')

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
                <li>Contact our support team at <a href="mailto:hello@homei.com.bd">hello@homei.com.bd</a> or call <a href="tel:+8801787656135">+880 1787656135</a> within 14 days of delivery.</li>
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
                <p>📞 <a href="tel:+8801787656135">+880 1787656135</a> (Sat–Thu, 10AM–8PM)</p>
                <p>✉️ <a href="mailto:hello@homei.com.bd">hello@homei.com.bd</a></p>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')
@include('partials.cart-drawer')
@endsection
