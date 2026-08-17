<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Cart;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // ============================
            // 0. CHECK IF ALREADY SEEDED
            // ============================
            if (Role::exists()) {
                return;
            }

            // ============================
            // 1. ROLES
            // ============================
            $rolesData = [
                ['name' => 'super_admin', 'description' => 'Full system access'],
                ['name' => 'admin', 'description' => 'Manage products, orders, inventory'],
                ['name' => 'manager', 'description' => 'Limited management access'],
                ['name' => 'customer', 'description' => 'Shop and place orders'],
            ];
            
            $roles = [];
            foreach ($rolesData as $role) {
                $roles[$role['name']] = Role::create($role);
            }

            // ============================
            // 2. PERMISSIONS
            // ============================
            $subjects = ['User', 'Product', 'Category', 'Order', 'Inventory', 'Payment', 'Coupon', 'Review', 'Cart', 'AuditLog', 'Report'];
            $actions = ['create', 'read', 'update', 'delete'];
            
            $permissions = [];
            foreach ($subjects as $subject) {
                foreach ($actions as $action) {
                    $key = "{$action}:{$subject}";
                    $permissions[$key] = Permission::create([
                        'action' => $action,
                        'subject' => $subject,
                        'description' => "{$action} {$subject}",
                    ]);
                }
            }

            // Add manage all permission
            $permissions['manage:all'] = Permission::create([
                'action' => 'manage',
                'subject' => 'all',
                'description' => 'Full access to everything',
            ]);

            // ============================
            // 3. ROLE_PERMISSIONS
            // ============================
            
            // super_admin gets manage:all
            $roles['super_admin']->permissions()->attach($permissions['manage:all']->id);

            // admin gets everything except User delete and AuditLog write/update/delete
            $adminSubjects = ['Product', 'Category', 'Order', 'Inventory', 'Payment', 'Coupon', 'Review'];
            foreach ($adminSubjects as $subj) {
                foreach ($actions as $act) {
                    $key = "{$act}:{$subj}";
                    if (isset($permissions[$key])) {
                        $roles['admin']->permissions()->attach($permissions[$key]->id);
                    }
                }
            }
            $roles['admin']->permissions()->attach($permissions['read:User']->id);
            $roles['admin']->permissions()->attach($permissions['read:AuditLog']->id);
            $roles['admin']->permissions()->attach($permissions['read:Report']->id);

            // manager: read/update Product, Category, Order, Inventory, read Review
            $managerPerms = [
                'read:Product', 'update:Product', 'read:Category', 'read:Order', 'update:Order',
                'read:Inventory', 'update:Inventory', 'read:Review',
            ];
            foreach ($managerPerms as $perm) {
                if (isset($permissions[$perm])) {
                    $roles['manager']->permissions()->attach($permissions[$perm]->id);
                }
            }

            // customer: read Product/Category, CRUD Cart, create/read Order, CRUD own Review
            $customerPerms = [
                'read:Product', 'read:Category', 'create:Cart', 'read:Cart', 'update:Cart', 'delete:Cart',
                'create:Order', 'read:Order', 'create:Review', 'read:Review', 'update:Review', 'delete:Review',
            ];
            foreach ($customerPerms as $perm) {
                if (isset($permissions[$perm])) {
                    $roles['customer']->permissions()->attach($permissions[$perm]->id);
                }
            }

            // ============================
            // 4. USERS
            // ============================
            $adminUser = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@homei.com',
                'password' => Hash::make('Admin@123'),
                'phone' => '+880 1XXX-XXXXXX',
                'role_id' => $roles['super_admin']->id,
                'is_active' => true,
            ]);

            $customerUser = User::create([
                'name' => 'Demo Customer',
                'email' => 'customer@homei.com',
                'password' => Hash::make('Customer@123'),
                'phone' => '+880 1XXX-XXXXXX',
                'role_id' => $roles['customer']->id,
                'is_active' => true,
            ]);

            // Create cart for demo customer
            Cart::create(['user_id' => $customerUser->id]);

            // ============================
            // 5. CATEGORIES
            // ============================
            $categoriesData = [
                ['name' => 'Bookshelves', 'slug' => 'bookshelves', 'description' => 'Wooden bookshelves and display units', 'image' => '/assets/images/category-bookshelf.png', 'sort_order' => 1, 'is_active' => true],
                ['name' => 'Dining', 'slug' => 'dining', 'description' => 'Dining tables and chair sets', 'image' => '/assets/images/category-dining.png', 'sort_order' => 2, 'is_active' => true],
                ['name' => 'Bedroom', 'slug' => 'bedroom', 'description' => 'Bed frames, nightstands, and wardrobes', 'image' => '/assets/images/category-bedroom.png', 'sort_order' => 3, 'is_active' => true],
                ['name' => 'Storage', 'slug' => 'storage', 'description' => 'TV units, cabinets, and storage solutions', 'image' => '/assets/images/category-storage.png', 'sort_order' => 4, 'is_active' => true],
                ['name' => 'Study & Office', 'slug' => 'study-office', 'description' => 'Study desks and office furniture', 'image' => '/assets/images/category-desk.png', 'sort_order' => 5, 'is_active' => true],
                ['name' => 'Home Décor', 'slug' => 'home-decor', 'description' => 'Decorative items and accessories', 'image' => '/assets/images/hero-living-room.png', 'sort_order' => 6, 'is_active' => true],
            ];

            $categories = [];
            foreach ($categoriesData as $cat) {
                $categories[$cat['slug']] = Category::create($cat);
            }

            // ============================
            // 6. SAMPLE PRODUCTS
            // ============================
            $productsData = [
                [
                    'name' => 'Nordic Oak Ladder Shelf', 'slug' => 'nordic-oak-ladder-shelf', 'description' => 'A beautifully crafted 5-tier ladder shelf made from premium Nordic Oak. Perfect for displaying books, plants, and decorative items.', 'short_description' => 'Handcrafted 5-tier oak ladder bookshelf',
                    'price' => 8500, 'compare_price' => 10200, 'cost_price' => 5500, 'sku' => 'HI-BS-001', 'category_id' => $categories['bookshelves']->id,
                    'image' => '/assets/images/category-bookshelf.png', 'badge' => 'New', 'is_active' => true, 'is_featured' => true, 'avg_rating' => 4.90, 'review_count' => 48, 'sold_count' => 342,
                ],
                [
                    'name' => 'Walnut Dining Set — 4 Seater', 'slug' => 'walnut-dining-set-4-seater', 'description' => 'Elegant walnut wood dining table with 4 matching chairs. Seats 4 comfortably with a smooth lacquer finish.', 'short_description' => '4-seater walnut dining table with chairs',
                    'price' => 28000, 'compare_price' => 32500, 'cost_price' => 18000, 'sku' => 'HI-DN-001', 'category_id' => $categories['dining']->id,
                    'image' => '/assets/images/category-dining.png', 'badge' => 'Hot', 'is_active' => true, 'is_featured' => true, 'avg_rating' => 4.95, 'review_count' => 72, 'sold_count' => 278,
                ],
                [
                    'name' => 'Oak Slatted Bed Frame — King', 'slug' => 'oak-slatted-bed-frame-king', 'description' => 'Solid oak king-size bed frame with a slatted design for modern minimalist bedrooms. Durable and elegant.', 'short_description' => 'King-size solid oak slatted bed frame',
                    'price' => 35000, 'compare_price' => null, 'cost_price' => 22000, 'sku' => 'HI-BR-001', 'category_id' => $categories['bedroom']->id,
                    'image' => '/assets/images/category-bedroom.png', 'badge' => 'New', 'is_active' => true, 'is_featured' => true, 'avg_rating' => 4.20, 'review_count' => 35, 'sold_count' => 156,
                ],
                [
                    'name' => 'Minimalist Oak TV Console', 'slug' => 'minimalist-oak-tv-console', 'description' => 'Sleek, modern TV console crafted from solid oak with cable management. Fits TVs up to 65 inches.', 'short_description' => 'Minimalist oak TV console for modern living rooms',
                    'price' => 18500, 'compare_price' => 22000, 'cost_price' => 12000, 'sku' => 'HI-ST-001', 'category_id' => $categories['storage']->id,
                    'image' => '/assets/images/category-storage.png', 'badge' => 'Sale', 'is_active' => true, 'is_featured' => true, 'avg_rating' => 4.95, 'review_count' => 56, 'sold_count' => 215,
                ],
                [
                    'name' => 'Cane-Woven Writing Desk', 'slug' => 'cane-woven-writing-desk', 'description' => 'Elegant writing desk featuring a cane-woven drawer front. Compact design perfect for home offices and study rooms.', 'short_description' => 'Compact cane-woven writing desk',
                    'price' => 14200, 'compare_price' => null, 'cost_price' => 9000, 'sku' => 'HI-SD-001', 'category_id' => $categories['study-office']->id,
                    'image' => '/assets/images/category-desk.png', 'badge' => 'New', 'is_active' => true, 'is_featured' => false, 'avg_rating' => 4.95, 'review_count' => 29, 'sold_count' => 98,
                ],
                [
                    'name' => 'Wall-Mounted Floating Shelf Set', 'slug' => 'wall-mounted-floating-shelf-set', 'description' => 'Set of 3 floating shelves in different sizes. Easy to mount and perfect for any wall space.', 'short_description' => '3-piece wall-mounted floating shelf set',
                    'price' => 4800, 'compare_price' => 6000, 'cost_price' => 2800, 'sku' => 'HI-BS-002', 'category_id' => $categories['bookshelves']->id,
                    'image' => '/assets/images/category-bookshelf.png', 'badge' => 'Best Seller', 'is_active' => true, 'is_featured' => true, 'avg_rating' => 5.00, 'review_count' => 94, 'sold_count' => 420,
                ],
                [
                    'name' => 'Oak Bedside Nightstand', 'slug' => 'oak-bedside-nightstand', 'description' => 'Compact bedside table with a single drawer and open shelf. Matches our Oak bed frame collection.', 'short_description' => 'Oak bedside nightstand with drawer',
                    'price' => 6500, 'compare_price' => null, 'cost_price' => 4000, 'sku' => 'HI-BR-002', 'category_id' => $categories['bedroom']->id,
                    'image' => '/assets/images/category-bedroom.png', 'badge' => null, 'is_active' => true, 'is_featured' => false, 'avg_rating' => 4.10, 'review_count' => 41, 'sold_count' => 187,
                ],
                [
                    'name' => 'Round Walnut Coffee Table', 'slug' => 'round-walnut-coffee-table', 'description' => 'Beautiful round coffee table crafted from solid walnut. Features tapered legs and a smooth finish.', 'short_description' => 'Round walnut coffee table with tapered legs',
                    'price' => 9800, 'compare_price' => 12000, 'cost_price' => 6500, 'sku' => 'HI-DN-002', 'category_id' => $categories['dining']->id,
                    'image' => '/assets/images/category-dining.png', 'badge' => 'Sale', 'is_active' => true, 'is_featured' => false, 'avg_rating' => 4.95, 'review_count' => 63, 'sold_count' => 195,
                ],
            ];

            $products = [];
            foreach ($productsData as $product) {
                $products[] = Product::create($product);
            }

            // ============================
            // 7. INVENTORY
            // ============================
            $quantities = [45, 12, 28, 8, 35, 60, 22, 15];
            foreach ($products as $i => $product) {
                Inventory::create([
                    'product_id' => $product->id,
                    'quantity' => $quantities[$i] ?? 20,
                    'low_stock_threshold' => 10,
                    'warehouse_location' => 'Dhaka Main',
                ]);
            }

            // ============================
            // 8. SAMPLE ORDERS
            // ============================
            $subtotals = [36500, 14200, 9800];
            $shippingCosts = [0, 200, 0];
            $totals = [36500, 14400, 9800];
            $statuses = ['delivered', 'processing', 'pending'];
            $paymentMethods = ['bkash', 'cod', 'nagad'];
            $paymentStatuses = ['completed', 'pending', 'pending'];
            $paymentTxns = ['BK-2026-001', null, null];

            $orders = [];
            for ($k = 0; $k < 3; $k++) {
                $orderNumber = 'HI-2026000' . ($k + 1);
                $orders[$k] = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => $customerUser->id,
                    'status' => $statuses[$k],
                    'subtotal' => $subtotals[$k],
                    'discount_amount' => 0,
                    'shipping_cost' => $shippingCosts[$k],
                    'total' => $totals[$k],
                    'shipping_name' => 'Demo Customer',
                    'shipping_phone' => '+880 1XXX-XXXXXX',
                    'shipping_address' => 'House 42, Road 5, Dhanmondi',
                    'shipping_city' => 'Dhaka',
                    'created_at' => now()->subDays(7 - ($k * 2)),
                ]);
            }

            // Order Items
            OrderItem::create([
                'order_id' => $orders[0]->id,
                'product_id' => $products[0]->id,
                'product_name' => 'Nordic Oak Ladder Shelf',
                'quantity' => 1,
                'unit_price' => 8500,
                'total_price' => 8500,
            ]);
            OrderItem::create([
                'order_id' => $orders[0]->id,
                'product_id' => $products[1]->id,
                'product_name' => 'Walnut Dining Set — 4 Seater',
                'quantity' => 1,
                'unit_price' => 28000,
                'total_price' => 28000,
            ]);
            OrderItem::create([
                'order_id' => $orders[1]->id,
                'product_id' => $products[4]->id,
                'product_name' => 'Cane-Woven Writing Desk',
                'quantity' => 1,
                'unit_price' => 14200,
                'total_price' => 14200,
            ]);
            OrderItem::create([
                'order_id' => $orders[2]->id,
                'product_id' => $products[7]->id,
                'product_name' => 'Round Walnut Coffee Table',
                'quantity' => 1,
                'unit_price' => 9800,
                'total_price' => 9800,
            ]);

            // Payments
            for ($k = 0; $k < 3; $k++) {
                Payment::create([
                    'order_id' => $orders[$k]->id,
                    'method' => $paymentMethods[$k],
                    'status' => $paymentStatuses[$k],
                    'amount' => $totals[$k],
                    'transaction_id' => $paymentTxns[$k],
                    'paid_at' => $paymentStatuses[$k] === 'completed' ? now()->subDays(7) : null,
                ]);
            }
        });

        // Seed email templates (runs even if other data exists)
        $this->call(EmailTemplateSeeder::class);
    }
}
