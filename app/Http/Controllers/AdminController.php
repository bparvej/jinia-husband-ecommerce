<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Models\Inventory;
use App\Models\InventoryLedger;
use App\Models\Category;
use App\Models\Role;

class AdminController extends Controller
{
    // --- ADMIN: Dashboard ---
    public function dashboard(Request $request)
    {
        // 1. Core counters
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Order::whereNotIn('status', ['cancelled', 'refunded'])->sum('total');
        $totalProducts = Product::where('is_active', true)->count();
        $totalCustomers = User::whereHas('role', function($q) {
            $q->where('name', 'customer');
        })->count();

        // 2. Recent orders
        $recentOrders = Order::with(['user', 'payment'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

// 3. Low stock inventory
        $lowStock = Inventory::with('product')
            ->whereRaw('quantity <= low_stock_threshold')
            ->whereNotNull('product_id')
            ->get();

        // 4. Sales report for last 7 days
        $endDate = now();
        $startDate = now()->subDays(7);
        
        $revenueChart = Order::whereNotIn('status', ['cancelled', 'refunded'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE(created_at) as date, SUM(total) as revenue, COUNT(id) as orders")
            ->groupByRaw("DATE(created_at)")
            ->orderByRaw("DATE(created_at) ASC")
            ->get();

        $viewData = [
            'title' => 'Dashboard — HomeI Admin',
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'totalRevenue' => floatval($totalRevenue),
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'recentOrders' => $recentOrders,
            'lowStock' => $lowStock,
            'revenueChart' => $revenueChart,
            // Supporting legacy template properties
            'stats' => (object)[
                'total_orders' => $totalOrders,
                'pending_orders' => $pendingOrders,
                'total_revenue' => $totalRevenue
            ]
        ];

        return view('admin.dashboard', $viewData);
    }

    // --- ADMIN: Orders Listing ---
    public function adminOrders(Request $request)
    {
        $query = Order::with(['user', 'payment']);

        $search = $request->query('search');
        $status = $request->query('status');

        if ($search) {
            $query->where('order_number', 'LIKE', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(12);

        $viewData = [
            'orders' => $orders,
            'filters' => [
                'search' => $search,
                'status' => $status
            ],
            'title' => 'Orders — HomeI Admin'
        ];

        // HTMX request partial
        if ($request->headers->has('hx-request') && $request->query('_partial')) {
            return view('admin.orders.partials.order-table', $viewData);
        }

        return view('admin.orders.index', $viewData);
    }

    // --- ADMIN: Order Detail ---
    public function adminOrderDetail($id)
    {
        $order = Order::with(['user', 'items.product', 'payment', 'coupon'])->findOrFail($id);

        return view('admin.orders.detail', [
            'title' => "Order {$order->order_number} — HomeI Admin",
            'order' => $order
        ]);
    }

    // --- ADMIN: Update Order Status ---
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order = Order::with(['items', 'payment'])->findOrFail($id);
        $previousStatus = $order->status;
        $newStatus = $request->input('status');
        $order->status = $newStatus;
        $order->save();

        // Handle side effects based on status transitions
        DB::beginTransaction();
        try {
            // Delivered — mark payment as completed
            if ($newStatus === 'delivered' && $order->payment) {
                $order->payment->status = 'completed';
                $order->payment->paid_at = now();
                $order->payment->save();
            }

            // Cancelled or Refunded — restore inventory & mark payment refunded
            if (in_array($newStatus, ['cancelled', 'refunded']) && !in_array($previousStatus, ['cancelled', 'refunded'])) {
                foreach ($order->items as $item) {
                    Inventory::adjustStock(
                        $item->product_id,
                        $item->quantity,
                        'return',
                        'Restocked after order ' . $order->order_number . ' was ' . $newStatus,
                        'order',
                        $order->id
                    );
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->sold_count = max(0, $product->sold_count - $item->quantity);
                        $product->save();
                    }
                }
                if ($order->payment) {
                    $order->payment->status = 'refunded';
                    $order->payment->save();
                }
            }

            DB::commit();
            Log::info("Order status updated: {$order->order_number} {$previousStatus} -> {$newStatus}");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Order status update side effects failed: " . $e->getMessage());
        }

        if ($request->headers->has('hx-request')) {
            return view('admin.orders.partials.status-badge', [
                'order' => $order
            ]);
        }

        return redirect("/admin/orders/{$id}");
    }

    // --- ADMIN: Invoice Generator ---
    public function invoice($id)
    {
        $order = Order::with(['user', 'items.product', 'payment', 'coupon'])->findOrFail($id);

        return view('admin.orders.invoice', [
            'title' => "Invoice {$order->order_number}",
            'order' => $order
        ]);
    }

    // --- ADMIN: Inventory Listing ---
    public function adminInventory(Request $request)
    {
        $query = Inventory::with('product');

        $search = $request->query('search');
        if ($search) {
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        $inventory = $query->orderBy('created_at', 'desc')->paginate(12);

        $viewData = [
            'inventory' => $inventory,
            'filters' => [
                'search' => $search
            ],
            'title' => 'Inventory — HomeI Admin'
        ];

        if ($request->headers->has('hx-request')) {
            return view('admin.inventory.partials.inventory-table', $viewData);
        }

        return view('admin.inventory.index', $viewData);
    }

    // --- ADMIN: Update Stock (HTMX from inventory list) ---
    public function updateInventoryStock(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:0|max:9999999',
            ]);

            $inventory = Inventory::where('product_id', $id)->first();
            if (!$inventory) {
                $inventory = Inventory::create([
                    'product_id' => $id,
                    'quantity' => 0,
                    'low_stock_threshold' => 10,
                    'warehouse_location' => 'Dhaka Main',
                ]);
            }

            $delta = intval($request->input('quantity')) - $inventory->quantity;

            Inventory::adjustStock(
                $id,
                $delta,
                'adjustment',
                'Manual stock update to ' . intval($request->input('quantity'))
            );

            if ($request->headers->has('hx-request')) {
                return '<span class="toast toast-success">Stock updated!</span>';
            }

            return redirect('/admin/inventory');
        } catch (Exception $e) {
            if ($request->headers->has('hx-request')) {
                return response('<span class="toast toast-error">' . e($e->getMessage()) . '</span>', 400);
            }
            return redirect('/admin/inventory')->with('error', $e->getMessage());
        }
    }

    // --- ADMIN: Inventory Ledger ---
    public function inventoryLedger(Request $request)
    {
        $query = InventoryLedger::with(['product', 'user']);

        $search = $request->query('search');
        if ($search) {
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        $type = $request->query('type');
        if ($type) {
            $query->where('type', $type);
        }

        $dateFrom = $request->query('date_from');
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        $dateTo = $request->query('date_to');
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $ledger = $query->orderBy('created_at', 'desc')->paginate(20);

        $summary = (object)[
            'total_in' => InventoryLedger::where('quantity', '>', 0)->sum('quantity'),
            'total_out' => InventoryLedger::where('quantity', '<', 0)->sum('quantity'),
        ];

        $viewData = [
            'ledger' => $ledger,
            'summary' => $summary,
            'filters' => [
                'search' => $search,
                'type' => $type,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
            'title' => 'Inventory Ledger — HomeI Admin'
        ];

        if ($request->headers->has('hx-request')) {
            return view('admin.inventory.partials.ledger-table', $viewData);
        }

        return view('admin.inventory.ledger', $viewData);
    }

    // --- ADMIN: Users Listing ---
    public function adminUsers(Request $request)
    {
        $query = User::with('role');

        $search = $request->query('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $roleId = $request->query('role_id');
        if ($roleId) {
            $query->where('role_id', $roleId);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(12);
        $roles = Role::all();

        $viewData = [
            'users' => $users,
            'roles' => $roles,
            'filters' => [
                'search' => $search,
                'role_id' => $roleId
            ],
            'title' => 'Users — HomeI Admin'
        ];

        if ($request->headers->has('hx-request')) {
            return view('admin.users.partials.users-table', $viewData);
        }

        return view('admin.users.index', $viewData);
    }


    // --- ADMIN: Reports & Analytics ---
    public function adminReports(Request $request)
    {
        $range = $request->query('range', '30days');
        
        $endDate = now();
        $startDate = now();

        if ($range === '7days') {
            $startDate = now()->subDays(7);
        } elseif ($range === '12months') {
            $startDate = now()->subMonths(12);
        } else {
            $startDate = now()->subDays(30);
        }

        // Sales Report
        $salesData = Order::whereNotIn('status', ['cancelled', 'refunded'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE(created_at) as date, SUM(total) as revenue, COUNT(id) as orders")
            ->groupByRaw("DATE(created_at)")
            ->orderByRaw("DATE(created_at) ASC")
            ->get();

        // Top products
        $topProducts = OrderItem::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("product_name, SUM(quantity) as quantity, SUM(total_price) as revenue")
            ->groupBy('product_name')
            ->orderByRaw("SUM(quantity) DESC")
            ->take(5)
            ->get();

        // Category Sales
        $categorySales = OrderItem::whereBetween('order_items.created_at', [$startDate, $endDate])
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw("categories.name as category_name, SUM(order_items.total_price) as revenue")
            ->groupBy('categories.name')
            ->get();

        // Payment Method Sales
        $paymentSales = Order::whereNotIn('orders.status', ['cancelled', 'refunded'])
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->join('payments', 'orders.id', '=', 'payments.order_id')
            ->selectRaw("payments.method as payment_method, COUNT(orders.id) as orders, SUM(orders.total) as revenue")
            ->groupBy('payments.method')
            ->get();

        // Summary calculations
        $totalRevenue = $salesData->sum('revenue');
        $totalOrders = $salesData->sum('orders');
        $totalProducts = Product::where('is_active', true)->count();
        $totalCustomers = User::whereHas('role', function($q) {
            $q->where('name', 'customer');
        })->count();

        // Average order value
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Period-over-period comparison (previous same-length period)
        $periodLength = $startDate->diffInDays($endDate);
        $prevStartDate = (clone $startDate)->subDays($periodLength);
        $prevPeriodRevenue = Order::whereNotIn('status', ['cancelled', 'refunded'])
            ->whereBetween('created_at', [$prevStartDate, $startDate])
            ->sum('total');
        $revenueGrowth = $prevPeriodRevenue > 0
            ? round((($totalRevenue - $prevPeriodRevenue) / $prevPeriodRevenue) * 100, 1)
            : 100;

        $sales = (object)[
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'avgOrderValue' => $avgOrderValue,
            'revenueGrowth' => $revenueGrowth,
        ];

        // Build chart labels & values for the frontend
        $chartDates = $salesData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'));
        $chartRevenue = $salesData->pluck('revenue')->map(fn($v) => floatval($v));
        $chartOrders = $salesData->pluck('orders');

        if ($request->headers->has('hx-request')) {
            return view('admin.reports.partials.sales-report', [
                'salesData' => $salesData,
                'topProducts' => $topProducts,
                'categorySales' => $categorySales,
                'paymentSales' => $paymentSales,
                'sales' => $sales,
                'chartDates' => $chartDates,
                'chartRevenue' => $chartRevenue,
                'chartOrders' => $chartOrders,
                'range' => $range
            ]);
        }

        return view('admin.reports.index', [
            'title' => 'Reports — HomeI Admin',
            'sales' => $sales,
            'salesData' => $salesData,
            'topProducts' => $topProducts,
            'categorySales' => $categorySales,
            'paymentSales' => $paymentSales,
            'chartDates' => $chartDates,
            'chartRevenue' => $chartRevenue,
            'chartOrders' => $chartOrders,
            'range' => $range
        ]);
    }

    // --- Resources: Feature List ---
    public function features()
    {
        $content = file_get_contents(base_path('feature.md'));
        $lines = explode("\n", $content);
        $html = '';
        $inList = false;
        $inTable = false;
        $inCode = false;
        $listType = '';

        foreach ($lines as $line) {
            $trimmed = trim($line);

            // Code block
            if (str_starts_with($trimmed, '```')) {
                if ($inCode) { $html .= "</pre>\n"; $inCode = false; } else { $html .= "<pre class=\"code-block\">"; $inCode = true; }
                continue;
            }
            if ($inCode) { $html .= htmlspecialchars($line) . "\n"; continue; }

            // Close list if not a list item
            if ($inList && !str_starts_with($trimmed, '- ') && !str_starts_with($trimmed, '* ') && !str_starts_with($trimmed, '1. ')) {
                $html .= $listType === 'ol' ? "</ol>\n" : "</ul>\n";
                $inList = false;
            }

            // Close table
            if ($inTable && (!str_contains($line, '|') || str_starts_with($trimmed, '---'))) {
                if (str_starts_with($trimmed, '---')) continue;
                $html .= "</tbody></table>\n";
                $inTable = false;
            }

            // Heading
            if (str_starts_with($line, '### ')) { $html .= "<h3>" . htmlspecialchars(substr($line, 4)) . "</h3>\n"; }
            elseif (str_starts_with($line, '## ')) { $html .= "<h2>" . htmlspecialchars(substr($line, 3)) . "</h2>\n"; }
            elseif (str_starts_with($line, '# ')) { $html .= "<h1>" . htmlspecialchars(substr($line, 2)) . "</h1>\n"; }

            // Table
            elseif (str_contains($line, '|') && preg_match('/^\|.+\|$/', $trimmed)) {
                if (!$inTable) {
                    $html .= "<table><thead><tr>";
                    $cols = explode('|', trim($trimmed, '|'));
                    foreach ($cols as $c) $html .= "<th>" . htmlspecialchars(trim($c)) . "</th>";
                    $html .= "</tr></thead><tbody>\n";
                    $inTable = true;
                } else {
                    $cols = explode('|', trim($trimmed, '|'));
                    $html .= "<tr>";
                    foreach ($cols as $c) $html .= "<td>" . htmlspecialchars(trim($c)) . "</td>";
                    $html .= "</tr>\n";
                }
            }
            // Sep line in table
            elseif ($inTable && str_starts_with($trimmed, '|---')) { continue; }

            // List item
            elseif (str_starts_with($trimmed, '- ') || str_starts_with($trimmed, '* ')) {
                if (!$inList) { $html .= "<ul>\n"; $inList = true; $listType = 'ul'; }
                $html .= "<li>" . htmlspecialchars(substr($trimmed, 2)) . "</li>\n";
            }
            elseif (preg_match('/^\d+\.\s/', $trimmed)) {
                if (!$inList) { $html .= "<ol>\n"; $inList = true; $listType = 'ol'; }
                $html .= "<li>" . htmlspecialchars(preg_replace('/^\d+\.\s/', '', $trimmed)) . "</li>\n";
            }

            // Bold line
            elseif (str_starts_with($trimmed, '**') && str_ends_with($trimmed, '**')) {
                $html .= "<p><strong>" . htmlspecialchars(trim($trimmed, '*')) . "</strong></p>\n";
            }

            // Empty line
            elseif (empty($trimmed)) { $html .= "<br>\n"; }

            // Paragraph
            else { $html .= "<p>" . htmlspecialchars($trimmed) . "</p>\n"; }
        }

        if ($inList) $html .= $listType === 'ol' ? "</ol>\n" : "</ul>\n";
        if ($inTable) $html .= "</tbody></table>\n";
        if ($inCode) $html .= "</pre>\n";

        return view('admin.resources.features', [
            'title' => 'Features — CartLite',
            'content' => $html
        ]);
    }

    // --- Resources: Brochure ---
    public function brochure()
    {
        return view('admin.resources.brochure', [
            'title' => 'Brochure — CartLite'
        ]);
    }

    // --- Resources: Admin SOP Guide ---
    public function adminGuide()
    {
        return view('admin.resources.guide', [
            'title' => 'Admin Guide SOP — CartLite'
        ]);
    }
}
