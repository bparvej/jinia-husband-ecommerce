@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title">CartLite — Admin Panel User Guideline SOP</h1>
        <p class="page-subtitle">Standard operating procedure for managing your e-commerce store</p>
    </div>
    <div class="page-header-actions">
        <button class="btn btn-primary-admin" onclick="window.print()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Print / Save PDF
        </button>
    </div>
</div>

<div class="guide-page">

    {{-- Cover --}}
    <div class="guide-cover">
        <p class="guide-cover-tag">Standard Operating Procedure</p>
        <h1>CartLite Admin Panel<br>User Guideline</h1>
        <p>Complete guide to managing your e-commerce store</p>
        <div class="guide-cover-meta">Version 1.0 &bull; June 2026</div>
    </div>

    <div class="guide-toc">
        <h3>Table of Contents</h3>
        <ol>
            <li><a href="#sec-access">Accessing the Admin Panel</a></li>
            <li><a href="#sec-dashboard">Dashboard Overview</a></li>
            <li><a href="#sec-products">Managing Products</a></li>
            <li><a href="#sec-categories">Managing Categories</a></li>
            <li><a href="#sec-orders">Managing Orders</a></li>
            <li><a href="#sec-inventory">Managing Inventory</a></li>
            <li><a href="#sec-users">Managing Users</a></li>
            <li><a href="#sec-reports">Reports &amp; Analytics</a></li>
            <li><a href="#sec-invoice">Invoice Generation</a></li>
            <li><a href="#sec-roles">User Roles &amp; Permissions</a></li>
            <li><a href="#sec-trouble">Troubleshooting</a></li>
        </ol>
    </div>

    {{-- 1. Access --}}
    <h2 id="sec-access" class="guide-h2">1. Accessing the Admin Panel</h2>
    <h3 class="guide-h3">1.1 Login</h3>
    <div class="guide-step"><strong>Step 1:</strong> Open your browser and navigate to <code>https://yourdomain.com/login</code>.</div>
    <div class="guide-step"><strong>Step 2:</strong> Enter your registered email address and password.</div>
    <div class="guide-step"><strong>Step 3:</strong> Click the <strong>"Sign In"</strong> button.</div>
    <div class="guide-note">If you are the super admin, you will be automatically redirected to the admin dashboard. Regular customers are redirected to the homepage.</div>

    <h3 class="guide-h3">1.2 Default Credentials</h3>
    <table class="guide-table">
        <tr><th>Role</th><th>Email</th><th>Password</th></tr>
        <tr><td>Super Admin</td><td>admin@gmail.com</td><td>password</td></tr>
        <tr><td>Admin</td><td>+8801712345678</td><td>password</td></tr>
    </table>
    <div class="guide-warn">Change the default password immediately after your first login for security purposes.</div>

    {{-- 2. Dashboard --}}
    <h2 id="sec-dashboard" class="guide-h2">2. Dashboard Overview</h2>
    <p>The dashboard provides a real-time snapshot of your store&rsquo;s performance. It auto-refreshes every 30 seconds.</p>
    <ul class="guide-ul">
        <li><strong>Statistics Cards</strong> — Total Revenue, Total Orders, Total Products, Total Customers</li>
        <li><strong>Order Status Breakdown</strong> — Quick counts by status (pending, confirmed, processing, shipped, delivered, cancelled, refunded)</li>
        <li><strong>Recent Orders</strong> — Last 5 orders for quick access</li>
        <li><strong>Low Stock Alerts</strong> — Products below their low-stock threshold</li>
        <li><strong>Revenue Chart (7 Days)</strong> — Daily revenue trend line chart</li>
    </ul>
    <div class="guide-tip">Use the dashboard as your daily starting point. Check low stock alerts first, then review recent orders.</div>

    {{-- 3. Products --}}
    <h2 id="sec-products" class="guide-h2">3. Managing Products</h2>
    <p>Navigate to <span class="guide-badge">Products</span> in the sidebar.</p>
    <h3 class="guide-h3">3.1 Creating a New Product</h3>
    <div class="guide-step"><strong>Step 1:</strong> Click <strong>"Add New Product"</strong>.</div>
    <div class="guide-step"><strong>Step 2:</strong> Fill required fields: Product Name, Category, Selling Price, SKU.</div>
    <div class="guide-step"><strong>Step 3:</strong> Fill optional fields: Short Description, Description, Compare Price (for discount display), Cost Price, Badge (New, Sale, etc.).</div>
    <div class="guide-step"><strong>Step 4:</strong> Upload a main image and optional gallery images.</div>
    <div class="guide-step"><strong>Step 5:</strong> Toggle <strong>"Featured"</strong> for homepage feature section.</div>
    <div class="guide-step"><strong>Step 6:</strong> Set Stock Quantity and Low Stock Threshold.</div>
    <div class="guide-step"><strong>Step 7:</strong> Click <strong>"Save"</strong>.</div>
    <h3 class="guide-h3">3.2 Search &amp; Filter</h3>
    <p>Search by product name or SKU. Filter by category or active/inactive status. Results update automatically (HTMX-powered).</p>
    <h3 class="guide-h3">3.3 Edit &amp; Delete</h3>
    <p>Click <strong>"Edit"</strong> to modify a product. Click <strong>"Delete"</strong> to soft-delete it (removed from storefront but existing orders unaffected).</p>

    {{-- 4. Categories --}}
    <h2 id="sec-categories" class="guide-h2">4. Managing Categories</h2>
    <div class="guide-step"><strong>Step 1:</strong> Click <strong>"Add New Category"</strong>.</div>
    <div class="guide-step"><strong>Step 2:</strong> Enter Category Name (slug is auto-generated).</div>
    <div class="guide-step"><strong>Step 3:</strong> Optionally add Description, Image, and select a Parent Category for hierarchy.</div>
    <div class="guide-step"><strong>Step 4:</strong> Set Sort Order (lower numbers appear first).</div>
    <div class="guide-step"><strong>Step 5:</strong> Toggle Active to enable/disable.</div>
    <div class="guide-step"><strong>Step 6:</strong> Click <strong>"Save"</strong>.</div>
    <div class="guide-tip">Use parent categories to create a hierarchy. Example: "Furniture" → "Chairs", "Tables", "Sofas".</div>

    {{-- 5. Orders --}}
    <h2 id="sec-orders" class="guide-h2">5. Managing Orders</h2>
    <p>Navigate to <span class="guide-badge">Orders</span> in the sidebar.</p>
    <h3 class="guide-h3">5.1 Order List</h3>
    <p>Shows order number (<code>HI-YYYYMMDD-XXXXXX</code>), customer info, total, status badge, and date. Search by order number. Filter by status.</p>
    <h3 class="guide-h3">5.2 Order Detail &amp; Status Update</h3>
    <div class="guide-step"><strong>Step 1:</strong> Click any order number or <strong>"View"</strong> to see full details (items, shipping, payment).</div>
    <div class="guide-step"><strong>Step 2:</strong> Select a new status from the dropdown.</div>
    <div class="guide-step"><strong>Step 3:</strong> Click <strong>"Update Status"</strong>.</div>

    <h3 class="guide-h3">5.3 Status Side Effects</h3>
    <table class="guide-table">
        <tr><th>Status Change</th><th>Automatic Action</th></tr>
        <tr><td>Shipped → <span class="tag tag-delivered">Delivered</span></td><td>Payment marked as completed</td></tr>
        <tr><td>Any → <span class="tag tag-cancelled">Cancelled</span></td><td>Inventory restored, sold_count decreased, payment refunded</td></tr>
        <tr><td>Any → <span class="tag tag-refunded">Refunded</span></td><td>Inventory restored, sold_count decreased, payment refunded</td></tr>
    </table>
    <div class="guide-warn">Only change to Cancelled/Refunded when the order is actually cancelled. Inventory is restored automatically.</div>

    {{-- 6. Inventory --}}
    <h2 id="sec-inventory" class="guide-h2">6. Managing Inventory</h2>
    <p>Navigate to <span class="guide-badge">Inventory</span> in the sidebar. Shows all products with current stock levels.</p>
    <div class="guide-step"><strong>Update Stock:</strong> Click <strong>"Edit"</strong>, update Quantity and Low Stock Threshold, then <strong>"Update"</strong>.</div>
    <div class="guide-note">Inventory is auto-deducted on order placement and restored on cancellation. Manual updates are for corrections or restocking only.</div>

    {{-- 7. Users --}}
    <h2 id="sec-users" class="guide-h2">7. Managing Users</h2>
    <p>Navigate to <span class="guide-badge">Users</span> in the sidebar. Lists all registered users with role, email, phone, and last login date. Search by name or email.</p>

    {{-- 8. Reports --}}
    <h2 id="sec-reports" class="guide-h2">8. Reports &amp; Analytics</h2>
    <p>Navigate to <span class="guide-badge">Reports</span> in the sidebar.</p>
    <ul class="guide-ul">
        <li><strong>Date Range:</strong> 7 Days, 30 Days, or 12 Months</li>
        <li><strong>Summary Metrics:</strong> Total revenue, orders, active products, customers, average order value</li>
        <li><strong>Revenue Growth:</strong> Percentage change vs previous period</li>
        <li><strong>Daily Sales Chart:</strong> Revenue per day</li>
        <li><strong>Top Products:</strong> Top 5 by quantity sold</li>
        <li><strong>Category Breakdown:</strong> Revenue by category</li>
        <li><strong>Payment Methods:</strong> Revenue by COD, bKash, Nagad</li>
    </ul>
    <div class="guide-tip">Use 12-month view for strategic planning, 7-day view for daily operations.</div>

    {{-- 9. Invoice --}}
    <h2 id="sec-invoice" class="guide-h2">9. Invoice Generation</h2>
    <p>Access from the order list (document icon) or order detail page (<strong>"Generate Invoice"</strong> button).</p>
    <ul class="guide-ul">
        <li>Brand header with store name</li>
        <li>Order number, date, customer shipping info</li>
        <li>Itemized table: product names, quantities, unit prices, totals</li>
        <li>Order summary: subtotal, shipping, total</li>
        <li>Payment method and status</li>
        <li>Print button for physical copies</li>
    </ul>

    {{-- 10. Roles --}}
    <h2 id="sec-roles" class="guide-h2">10. User Roles &amp; Permissions</h2>
    <table class="guide-table">
        <tr><th>Role</th><th>Access Level</th></tr>
        <tr><td><strong>Super Admin</strong></td><td>Full system access</td></tr>
        <tr><td><strong>Admin</strong></td><td>Products, orders, inventory, coupons, reviews, payments</td></tr>
        <tr><td><strong>Manager</strong></td><td>Read/update products, categories, orders, inventory, reviews</td></tr>
        <tr><td><strong>Customer</strong></td><td>Shop, place orders, manage own cart/reviews</td></tr>
    </table>

    {{-- 11. Troubleshooting --}}
    <h2 id="sec-trouble" class="guide-h2">11. Troubleshooting</h2>
    <div class="guide-trouble">
        <h4>Cannot log in</h4>
        <p>Verify email/password. Check caps lock. Contact super admin to reset password.</p>
    </div>
    <div class="guide-trouble">
        <h4>Product not showing on storefront</h4>
        <p>Check product status is <strong>Active</strong>. Verify category is also <strong>Active</strong>. Confirm stock quantity &gt; 0.</p>
    </div>
    <div class="guide-trouble">
        <h4>Inventory not deducting</h4>
        <p>Inventory is deducted at checkout, not delivery. Verify inventory record exists for the product.</p>
    </div>
    <div class="guide-trouble">
        <h4>Need help?</h4>
        <p>Contact <a href="mailto:homeibd26@gmail.com">homeibd26@gmail.com</a> or call <a href="tel:+8801787656135">+880 1787656135</a>.</p>
    </div>

</div>

<style>
.guide-page { max-width: 900px; margin: 0 auto; font-size: 14px; line-height: 1.7; }

.guide-cover { background: linear-gradient(135deg, #2C1810 0%, #5C3D2E 100%); color: #fff; border-radius: 16px; padding: 50px 40px; text-align: center; margin-bottom: 30px; }
.guide-cover h1 { font-size: 32px; font-weight: 800; font-family: var(--font-heading); margin: 10px 0; line-height: 1.2; }
.guide-cover p { font-size: 16px; opacity: 0.85; }
.guide-cover-tag { display: inline-block; background: #C4956A; padding: 5px 20px; border-radius: 20px; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; }
.guide-cover-meta { margin-top: 20px; font-size: 13px; opacity: 0.6; }

.guide-toc { background: #F8F5F0; border-radius: 12px; padding: 24px; margin-bottom: 30px; }
.guide-toc h3 { font-size: 16px; font-weight: 700; color: #2C1810; margin-bottom: 10px; }
.guide-toc ol { margin: 0; padding-left: 20px; }
.guide-toc ol li { padding: 3px 0; }
.guide-toc ol li a { color: #5C3D2E; text-decoration: none; }
.guide-toc ol li a:hover { color: #C4956A; }

.guide-h2 { font-size: 20px; font-weight: 700; color: #2C1810; border-bottom: 3px solid #C4956A; padding-bottom: 8px; margin: 35px 0 15px; }
.guide-h3 { font-size: 15px; font-weight: 700; color: #5C3D2E; margin: 20px 0 8px; }
.guide-page p { margin: 8px 0; color: #333; }
.guide-ul { margin: 8px 0 8px 20px; }
.guide-ul li { margin: 5px 0; }

.guide-step { background: #F8F5F0; border-left: 4px solid #C4956A; padding: 10px 14px; margin: 8px 0; border-radius: 0 8px 8px 0; font-size: 13px; }
.guide-step strong { color: #5C3D2E; }

.guide-note { background: #FFF8E8; border-left: 4px solid #E6A817; padding: 10px 14px; margin: 10px 0; border-radius: 0 8px 8px 0; font-size: 13px; }
.guide-tip { background: #E8F4F8; border-left: 4px solid #17A2B8; padding: 10px 14px; margin: 10px 0; border-radius: 0 8px 8px 0; font-size: 13px; }
.guide-warn { background: #FDE8E8; border-left: 4px solid #DC3545; padding: 10px 14px; margin: 10px 0; border-radius: 0 8px 8px 0; font-size: 13px; }

.guide-table { width: 100%; border-collapse: collapse; margin: 12px 0; font-size: 13px; }
.guide-table th { background: #2C1810; color: #fff; padding: 8px 14px; text-align: left; }
.guide-table td { padding: 8px 14px; border-bottom: 1px solid #e0d5cc; }
.guide-table tr:nth-child(even) td { background: #F8F5F0; }

.guide-badge { display: inline-block; background: #2C1810; color: #fff; padding: 2px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; }

.guide-trouble { background: var(--admin-surface); border: 1px solid var(--admin-border); border-radius: 10px; padding: 14px 18px; margin: 8px 0; }
.guide-trouble h4 { font-size: 14px; font-weight: 700; color: #2C1810; margin-bottom: 4px; }
.guide-trouble p { font-size: 13px; color: #6B5B4E; margin: 0; }

.tag { display: inline-block; padding: 1px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
.tag-delivered { background: #D4EDDA; color: #155724; }
.tag-cancelled { background: #F8D7DA; color: #721C24; }
.tag-refunded { background: #E2E3E5; color: #383D41; }

@media print {
    .admin-sidebar, .admin-header, .page-header, .sidebar-toggle { display: none !important; }
    .admin-main { margin-left: 0 !important; }
    .guide-page { max-width: 100%; font-size: 12px; }
}
</style>
@endsection
