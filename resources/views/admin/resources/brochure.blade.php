@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title">CartLite — Brochure</h1>
        <p class="page-subtitle">Selling material & feature overview</p>
    </div>
    <div class="page-header-actions">
        <button class="btn btn-primary-admin" onclick="window.print()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Print / Save PDF
        </button>
    </div>
</div>

<div class="brochure-page">

    {{-- Cover --}}
    <div class="brochure-cover">
        <div class="cover-tag">E-commerce Solution</div>
        <h1 class="cover-title">Cart<span>Lite</span></h1>
        <p class="cover-subtitle">A complete, lightweight Laravel e-commerce platform<br>for small &amp; medium businesses in Bangladesh</p>
        <div class="cover-badges">
            <span>Laravel 9</span>
            <span>PHP 8.x</span>
            <span>MySQL</span>
            <span>HTMX + Alpine.js</span>
            <span>bKash / Nagad / COD</span>
        </div>
        <p class="cover-footer">Ready to deploy &bull; Fully customizable &bull; One-click setup</p>
    </div>

    {{-- Features Grid --}}
    <div class="brochure-section">
        <h2 class="section-heading">Everything You Need to Sell Online</h2>
        <div class="brochure-grid">
            <div class="brochure-card">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                </div>
                <h4>Product Management</h4>
                <p>Add, edit, organize products with categories, images, SKUs, badges, and pricing. Feature products, set discounts, manage variants.</p>
            </div>
            <div class="brochure-card">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </div>
                <h4>Cart &amp; Checkout</h4>
                <p>Full cart system with guest &amp; logged-in user support. Quick buy pages for social media campaigns. One-page checkout with city selection.</p>
            </div>
            <div class="brochure-card">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <h4>Order Management</h4>
                <p>Track orders from pending to delivered. Cancel/refund with automatic inventory restore. Search, filter, and status updates with smart side effects.</p>
            </div>
            <div class="brochure-card">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <h4>Inventory Tracking</h4>
                <p>Real-time stock tracking with low-stock alerts. Auto-deduct on order, restore on cancellation. Warehouse location support.</p>
            </div>
            <div class="brochure-card">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                </div>
                <h4>Payment Methods</h4>
                <p>Cash on Delivery, bKash, Nagad — the most popular payment options in Bangladesh. Visa/Mastercard schema ready for future integration.</p>
            </div>
            <div class="brochure-card">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <h4>Invoice Generation</h4>
                <p>Printable invoices with brand header, itemized order table, payment status, and shipping details. One-click print from admin panel.</p>
            </div>
            <div class="brochure-card">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
                <h4>Reports &amp; Analytics</h4>
                <p>Sales reports with daily revenue charts, top products, category breakdown, payment method analysis, and period-over-period growth comparison.</p>
            </div>
            <div class="brochure-card">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h4>Role-Based Access</h4>
                <p>Super admin, admin, manager, and customer roles with granular permissions. Full audit logging for all admin actions.</p>
            </div>
        </div>
    </div>

    {{-- Bonus Features --}}
    <div class="brochure-section">
        <div class="bonus-box">
            <h3>Bonus Features (Included)</h3>
            <div class="bonus-list">
                <span>Coupon / Discount system</span>
                <span>Product reviews &amp; ratings</span>
                <span>Audit logs for all actions</span>
                <span>User notifications</span>
                <span>Product variants</span>
                <span>Soft delete on all entities</span>
            </div>
        </div>
    </div>

    {{-- Tech Specs --}}
    <div class="brochure-section">
        <h2 class="section-heading">Technical Specifications</h2>
        <table class="specs-table">
            <tr><td>Framework</td><td>Laravel 9.x</td></tr>
            <tr><td>PHP Version</td><td>^8.0.2</td></tr>
            <tr><td>Database</td><td>MySQL / MariaDB</td></tr>
            <tr><td>Frontend</td><td>Blade, HTMX 2.0.4, Alpine.js 3.14.8</td></tr>
            <tr><td>Styling</td><td>Custom CSS (no Bootstrap/Tailwind)</td></tr>
            <tr><td>Authentication</td><td>Laravel Sanctum + Custom Session Auth</td></tr>
            <tr><td>Currency</td><td>BDT (Bangladeshi Taka)</td></tr>
            <tr><td>Shipping</td><td>Free for orders &ge; 5,000 BDT | Flat 200 BDT below</td></tr>
            <tr><td>Cart System</td><td>Dual-mode: Database (logged in) + Session (guest)</td></tr>
            <tr><td>Order Number</td><td>Auto-generated: HI-YYYYMMDD-RANDOM6</td></tr>
            <tr><td>Hosting</td><td>Standard cPanel / Laravel hosting</td></tr>
        </table>
    </div>

    {{-- Pricing --}}
    <div class="brochure-section pricing-section">
        <h2 class="section-heading" style="text-align:center;display:block;border:none;">Get Started Today</h2>
        <div class="pricing-box">
            <div class="price">15,000 <small>BDT</small></div>
            <p>Complete source code &bull; Deployment support &bull; 30 days bug fix</p>
        </div>
        <div class="contact-box">
            <p><strong>Contact for Demo &amp; Purchase</strong></p>
            <p>Phone: +880 1712-345678 &bull; Email: sales@homei.com.bd &bull; Web: homei.com.bd</p>
        </div>
    </div>

</div>

<style>
.brochure-page { max-width: 900px; margin: 0 auto; }

.brochure-cover { background: linear-gradient(135deg, #2C1810 0%, #5C3D2E 100%); color: #fff; border-radius: 16px; padding: 50px 40px; text-align: center; margin-bottom: 30px; }
.cover-tag { display: inline-block; background: #C4956A; padding: 5px 20px; border-radius: 20px; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; margin-bottom: 20px; }
.cover-title { font-size: 42px; font-weight: 800; font-family: var(--font-heading); margin-bottom: 10px; }
.cover-title span { color: #C4956A; }
.cover-subtitle { font-size: 16px; opacity: 0.85; margin-bottom: 25px; line-height: 1.5; }
.cover-badges { display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; margin-bottom: 25px; }
.cover-badges span { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 6px; font-size: 12px; }
.cover-footer { font-size: 13px; opacity: 0.6; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 20px; }

.brochure-section { margin-bottom: 30px; }
.section-heading { font-size: 22px; font-weight: 700; color: #2C1810; border-bottom: 3px solid #C4956A; padding-bottom: 8px; margin-bottom: 20px; display: inline-block; }

.brochure-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.brochure-card { background: var(--admin-surface); border: 1px solid var(--admin-border); border-radius: 12px; padding: 20px; border-left: 4px solid #C4956A; }
.brochure-card .card-icon { width: 36px; height: 36px; color: #C4956A; margin-bottom: 10px; }
.brochure-card .card-icon svg { width: 100%; height: 100%; }
.brochure-card h4 { font-size: 15px; font-weight: 700; color: #2C1810; margin-bottom: 6px; }
.brochure-card p { font-size: 13px; color: #6B5B4E; line-height: 1.6; }

.bonus-box { background: linear-gradient(135deg, #2C1810, #5C3D2E); color: #fff; border-radius: 12px; padding: 30px; }
.bonus-box h3 { font-size: 18px; font-weight: 700; margin-bottom: 15px; color: #fff; }
.bonus-list { display: flex; flex-wrap: wrap; gap: 10px; }
.bonus-list span { background: rgba(255,255,255,0.1); padding: 6px 16px; border-radius: 6px; font-size: 13px; }
.bonus-list span::before { content: "✓ "; color: #C4956A; font-weight: 700; }

.specs-table { width: 100%; border-collapse: collapse; background: var(--admin-surface); border-radius: 12px; overflow: hidden; }
.specs-table td { padding: 12px 16px; border-bottom: 1px solid var(--admin-border); font-size: 14px; }
.specs-table td:first-child { font-weight: 600; color: #2C1810; width: 180px; }
.specs-table tr:last-child td { border-bottom: none; }

.pricing-section { text-align: center; }
.pricing-box { background: var(--admin-surface); border: 2px solid #C4956A; border-radius: 16px; padding: 30px; margin-bottom: 20px; }
.pricing-box .price { font-size: 40px; font-weight: 800; color: #5C3D2E; }
.pricing-box .price small { font-size: 16px; font-weight: 400; color: #9B8C80; }
.pricing-box p { color: #6B5B4E; margin-top: 8px; font-size: 14px; }

.contact-box { background: var(--admin-bg); border-radius: 12px; padding: 20px; }
.contact-box p { font-size: 13px; color: #6B5B4E; margin: 4px 0; }
.contact-box strong { color: #2C1810; }

@media print {
    .admin-sidebar, .admin-header, .page-header, .sidebar-toggle { display: none !important; }
    .admin-main { margin-left: 0 !important; }
    .brochure-page { max-width: 100%; }
}
</style>
@endsection
