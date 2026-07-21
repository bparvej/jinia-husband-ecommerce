@extends('layouts.main')

@section('content')
@include('partials.header')

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
