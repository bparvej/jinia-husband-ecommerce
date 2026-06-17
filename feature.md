# CartLite — Lightweight Laravel E-commerce Solution

> A complete, ready-to-deploy e-commerce platform built with Laravel 9, designed for small to medium businesses in Bangladesh.

---

## Core Features

### Storefront
- Modern, responsive product listing with category filtering
- Product detail page with image gallery, zoom, and thumbnail viewer
- Shopping cart with guest and authenticated user support
- Full checkout flow with shipping info, order notes, payment selection
- Quick buy page (`/buy/{slug}`) for direct marketing/social media campaigns
- Automatic order number generation (`HI-YYYYMMDD-RANDOM6`)
- Guest checkout with auto-registration
- Search overlay (ready for implementation)

### Product Management
- Full CRUD with image upload, SKU, pricing
- Multiple price fields: selling price, compare price (for discounts), cost price
- Featured products and badge system
- Auto-slug generation with duplicate handling
- Active/inactive product toggle
- Soft delete support
- Product variants (schema ready)

### Category Management
- Hierarchical categories (parent-child)
- Custom sort ordering
- Category images
- Active/inactive toggle
- Auto-slug generation

### Order Management
- Complete order lifecycle: pending → confirmed → processing → shipped → delivered
- Cancellation and refund workflows
- Status-based side effects:
  - Delivered → marks payment as completed
  - Cancelled/Refunded → restores inventory, decrements sold count
- Search by order number
- Filter by status

### Inventory Management
- One-to-one inventory tracking per product
- Low stock threshold alerts
- Warehouse location tracking
- Quantity search
- Row-level stock updates

### Admin Dashboard
- Real-time metrics: total revenue, orders, products, customers
- Order status breakdown (pending, processing, delivered)
- Recent 5 orders quick view
- Low stock alerts
- 7-day revenue chart
- Auto-refresh every 30 seconds via HTMX

### Reports & Analytics
- Date range selection: 7 days, 30 days, 12 months
- Daily sales revenue chart
- Top 5 selling products by quantity
- Category-wise revenue breakdown
- Payment method sales breakdown
- Period-over-period revenue growth (%)
- Summary metrics: avg order value, active products

### Invoice System
- Printable order invoice
- Brand header, itemized table, status badge
- Payment details and shipping info
- Print-friendly layout

### Payment Methods
- Cash on Delivery (COD)
- bKash mobile wallet
- Nagad mobile wallet
- Visa/Mastercard (schema ready)

### User Management
- Role-based access: super_admin, admin, manager, customer
- User search by name/email
- Last login tracking
- Active/inactive user toggle
- Soft delete support

### Admin Panel
- Clean, modern Blade admin layout
- HTMX-powered partial page updates (no full reloads)
- Alpine.js for interactive UI elements
- Responsive sidebar navigation
- Inline status updates with toast notifications

---

## Technical Specifications

| Feature | Details |
|---------|---------|
| Framework | Laravel 9.x |
| PHP | ^8.0.2 |
| Database | MySQL / MariaDB |
| Frontend | Blade, HTMX 2.0.4, Alpine.js 3.14.8 |
| CSS | Custom stylesheet (no Bootstrap/Tailwind) |
| Currency | BDT (Bangladeshi Taka) |
| Auth | Laravel Sanctum + custom session auth |
| Cart | Dual-mode: DB (authenticated) + Session (guest) |
| Free Shipping | Orders ≥ 5,000 BDT |
| Shipping Cost | Flat 200 BDT (below threshold) |
| Shipping Cities | All 8 Bangladeshi divisions |

---

## Database Schema (17+ Tables)

`roles`, `permissions`, `role_permissions`, `users`, `categories`, `products`, `product_variants`, `inventory`, `coupons`, `orders`, `order_items`, `payments`, `carts`, `cart_items`, `reviews`, `audit_logs`, `notifications`

---

## Bonus Features (Schema Ready)

- ✅ Coupon/discount system with usage limits and expiry
- ✅ Product reviews and ratings
- ✅ Audit logging for all admin actions
- ✅ User notifications (order updates, stock alerts)
- ✅ Product variants (size, color, etc.)

---

## Deployment

- Hosted on standard cPanel/Laravel hosting
- Composer-based dependency management
- Single-command migration and seeding
- Environment-based configuration via `.env`

---

*CartLite — Built with Laravel. Made for Bangladesh.*
