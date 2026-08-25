<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Models\Role;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Inventory;
use App\Models\InventoryLedger;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Blade;
use App\Models\EmailTemplate;
use Illuminate\Validation\ValidationException;
use Exception;
use Throwable;

class StorefrontController extends Controller
{
    public function home()
    {
        $products = Product::where('is_active', true)->orderBy('created_at', 'desc')->take(8)->get();
        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $featuredProducts = Product::where('is_active', true)->where('is_featured', true)->take(8)->get();

        $totalProducts = Product::where('is_active', true)->count();
        $totalOrders = Order::where('status', 'delivered')->count();
        $avgRating = Product::where('is_active', true)->where('avg_rating', '>', 0)->avg('avg_rating');

        $bannerImage = Setting::get('banner_image', SettingsController::DEFAULT_BANNER);

        return view('pages.home', [
            'title' => 'HomeI — Cozy Living | Wooden & Home Decor Furniture',
            'products' => $products,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'avgRating' => round($avgRating, 1),
            'bannerImage' => $bannerImage
        ]);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        $products = Product::where('is_active', true)
            ->where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pages.category', [
            'title' => $category->name . ' — HomeI Cozy Living',
            'category' => $category,
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    // --- Cart Management ---
    public function getCartDrawer(Request $request)
    {
        return $this->renderCartDrawer($request);
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = intval($request->input('quantity', 1));

        if ($quantity < 1) {
            $quantity = 1;
        }

        $product = Product::findOrFail($productId);

        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
            }
        } else {
            // Guest session cart
            $cartItems = $request->session()->get('cart.items', []);
            $found = false;
            foreach ($cartItems as &$item) {
                if (intval($item['product_id']) === intval($productId)) {
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }
            unset($item);
            if (!$found) {
                $cartItems[] = [
                    'product_id' => intval($productId),
                    'quantity' => $quantity
                ];
            }
            $request->session()->put('cart.items', $cartItems);
        }

        return $this->renderCartDrawer($request, 'open-cart');
    }

    public function updateCartQuantity(Request $request, $productId)
    {
        $quantity = intval($request->input('quantity'));

        if ($quantity < 1) {
            $quantity = 1;
        }

        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();
            if ($cart) {
                $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();
                if ($cartItem) {
                    $cartItem->quantity = $quantity;
                    $cartItem->save();
                }
            }
        } else {
            $cartItems = $request->session()->get('cart.items', []);
            foreach ($cartItems as &$item) {
                if (intval($item['product_id']) === intval($productId)) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            unset($item);
            $request->session()->put('cart.items', $cartItems);
        }

        return $this->renderCartDrawer($request);
    }

    public function removeFromCart(Request $request, $productId)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();
            if ($cart) {
                CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->delete();
            }
        } else {
            $cartItems = $request->session()->get('cart.items', []);
            $cartItems = array_filter($cartItems, function ($item) use ($productId) {
                return intval($item['product_id']) !== intval($productId);
            });
            $request->session()->put('cart.items', array_values($cartItems));
        }

        return $this->renderCartDrawer($request);
    }

    // --- Shared: Build cart data for the current user/session ---
    private function getCartData(Request $request): array
    {
        $cartItems = [];

        if (Auth::check()) {
            $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

            if ($cart) {
                foreach ($cart->items as $item) {
                    if (!$item->product) {
                        continue;
                    }
                    $cartItems[] = (object)[
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'Product' => $item->product,
                    ];
                }
            }
        } else {
            $sessionItems = $request->session()->get('cart.items', []);

            foreach ($sessionItems as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    continue;
                }
                $cartItems[] = (object)[
                    'id' => 'guest-' . $product->id,
                    'product_id' => $product->id,
                    'quantity' => intval($item['quantity']),
                    'Product' => $product,
                ];
            }
        }

        $cart = new \stdClass();
        $cart->CartItems = collect($cartItems);

        return [
            'cart' => $cart,
            'count' => $cart->CartItems->sum('quantity'),
        ];
    }

    private function renderCartDrawer(Request $request, ?string $trigger = null)
    {
        $cartData = $this->getCartData($request);

        $response = response()->view('partials.cart-drawer-content', [
            'cart' => $cartData['cart'],
            'count' => $cartData['count'],
            'csrfToken' => csrf_token()
        ]);

        if ($trigger) {
            $response->header('HX-Trigger', $trigger);
        }

        return $response;
    }

    // --- Order Processing ---
    public function checkout(Request $request)
    {
        try {
            $request->validate([
                'shipping_name' => 'required|string|max:100',
                'shipping_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string',
                'shipping_city' => 'required|string|max:100',
                'payment_method' => 'required|string',
            ]);

            $cartData = $this->getCartData($request);
            $cartItems = $cartData['cart']->CartItems;

            if (count($cartItems) === 0) {
                return $this->orderErrorResponse($request, 'Your cart is empty');
            }

            DB::beginTransaction();

            try {
                $userId = Auth::id();

                // Handle Guest Registration/Lookup
                if (!$userId) {
                    $shippingPhone = $request->input('shipping_phone');
                    $shippingEmail = $request->input('shipping_email');
                    $user = User::where('phone', $shippingPhone)->first();

                    if (!$user) {
                        $cleanPhone = preg_replace('/\s+/', '', $shippingPhone);
                        $guestEmail = !empty($shippingEmail) ? $shippingEmail : "guest_{$cleanPhone}@homei.com.bd";
                        $user = User::where('email', $guestEmail)->first();

                        if (!$user) {
                            $role = Role::where('name', 'customer')->first();
                            $roleId = $role ? $role->id : 4;
                            $dummyPassword = Hash::make('Guest@' . Str::random(6) . '2026');

                            $user = User::create([
                                'name' => $request->input('shipping_name'),
                                'email' => $guestEmail,
                                'password' => $dummyPassword,
                                'phone' => $shippingPhone,
                                'role_id' => $roleId,
                                'is_active' => true
                            ]);
                        }
                    } elseif (!empty($shippingEmail) && str_starts_with($user->email, 'guest_')) {
                        $user->update(['email' => $shippingEmail]);
                    }
                    $userId = $user->id;
                }

            // Aggregate cart items into one order
            $orderItemsData = [];
            $subtotal = 0;

            foreach ($cartItems as $cartItem) {
                $product = Product::lockForUpdate()->find($cartItem->product_id);
                if (!$product) {
                    throw new Exception("Product not found: " . $cartItem->product_id);
                }

                $inventory = Inventory::where('product_id', $product->id)->lockForUpdate()->first();
                if (!$inventory || $inventory->quantity < $cartItem->quantity) {
                    throw new Exception("Insufficient stock for " . $product->name);
                }

                // Deduct stock
                $inventory->quantity -= $cartItem->quantity;
                $inventory->save();

                // Increment sold count
                $product->sold_count += $cartItem->quantity;
                $product->save();

                $itemTotal = floatval($product->price) * $cartItem->quantity;
                $subtotal += $itemTotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $product->price,
                    'total_price' => $itemTotal,
                    'product' => $product
                ];
            }

            $shippingCity = $request->input('shipping_city', 'Dhaka');
            if ($subtotal >= 5000) {
                $shippingCost = 0;
            } elseif (strtolower($shippingCity) === 'dhaka') {
                $shippingCost = 80;
            } else {
                $shippingCost = 120;
            }
            $total = $subtotal + $shippingCost;

            // Generate order number
            $orderNumber = 'HI-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            // Create Order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $userId,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'shipping_name' => $request->input('shipping_name'),
                'shipping_phone' => $request->input('shipping_phone'),
                'shipping_address' => $request->input('shipping_address'),
                'shipping_city' => $shippingCity,
                'notes' => $request->input('notes')
            ]);

            // Save order items
            foreach ($orderItemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);

                Payment::create([
                    'order_id' => $order->id,
                    'method' => $request->input('payment_method'),
                    'status' => 'pending',
                    'amount' => $total
                ]);

                // Record sale debit in the inventory ledger (stock was already deducted above)
                InventoryLedger::create([
                    'product_id' => $itemData['product_id'],
                    'type' => 'sale',
                    'quantity' => -intval($itemData['quantity']),
                    'balance_after' => Inventory::where('product_id', $itemData['product_id'])->value('quantity') ?? 0,
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'note' => 'Sold with order ' . $orderNumber,
                    'user_id' => $order->user_id,
                ]);
            }

            // Clear Cart
            if (Auth::check()) {
                $dbCart = Cart::where('user_id', Auth::id())->first();
                if ($dbCart) {
                    CartItem::where('cart_id', $dbCart->id)->delete();
                }
            } else {
                $request->session()->forget('cart.items');
            }

            DB::commit();

            Log::info("Order placed successfully: " . $order->order_number);

            // Send confirmation emails AFTER commit, non-blocking (runs after the response is sent)
            foreach ($orderItemsData as $itemData) {
                $this->dispatchOrderEmails($order, $itemData['product'], intval($itemData['quantity']), $total, $orderNumber);
            }

            return $this->orderSuccessResponse($request, $orderNumber);

            } catch (Exception $e) {
                DB::rollBack();
                Log::error("Order placement failed: " . $e->getMessage(), [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
                return $this->orderErrorResponse($request, 'Order failed: ' . $e->getMessage());
            }
        } catch (ValidationException $e) {
            $messages = collect($e->errors())->flatten()->unique()->implode(' ');
            return $this->orderErrorResponse($request, 'Please correct the form: ' . $messages);
        }
    }

    // --- Quick Buy / Checkout Page (for digital marketing) ---
    public function quickBuy($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        return view('pages.checkout', [
            'title' => 'Buy ' . $product->name . ' — HomeI Cozy Living',
            'product' => $product,
            'categories' => $categories
        ]);
    }

    // --- Quick Checkout (direct product order from /buy/{slug}) ---
    public function quickCheckout(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'shipping_name' => 'required|string|max:100',
                'shipping_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string',
                'shipping_city' => 'required|string|max:100',
                'payment_method' => 'required|string'
            ]);

            $product = Product::findOrFail($request->input('product_id'));
            $quantity = intval($request->input('quantity', 1));

            DB::beginTransaction();

            try {
                $userId = Auth::id();

            if (!$userId) {
                $shippingPhone = $request->input('shipping_phone');
                $shippingEmail = $request->input('shipping_email');
                $user = User::where('phone', $shippingPhone)->first();

                if (!$user) {
                    $cleanPhone = preg_replace('/\s+/', '', $shippingPhone);
                    $guestEmail = !empty($shippingEmail) ? $shippingEmail : "guest_{$cleanPhone}@homei.com.bd";
                    $user = User::where('email', $guestEmail)->first();

                    if (!$user) {
                        $role = Role::where('name', 'customer')->first();
                        $roleId = $role ? $role->id : 4;
                        $dummyPassword = Hash::make('Guest@' . Str::random(6) . '2026');

                        $user = User::create([
                            'name' => $request->input('shipping_name'),
                            'email' => $guestEmail,
                            'password' => $dummyPassword,
                            'phone' => $shippingPhone,
                            'role_id' => $roleId,
                            'is_active' => true
                        ]);
                    }
                } elseif (!empty($shippingEmail) && str_starts_with($user->email, 'guest_')) {
                    $user->update(['email' => $shippingEmail]);
                }
                $userId = $user->id;
            }

            $product = Product::lockForUpdate()->find($product->id);
            $inventory = Inventory::where('product_id', $product->id)->lockForUpdate()->first();

            if (!$inventory || $inventory->quantity < $quantity) {
                throw new Exception("Insufficient stock for " . $product->name);
            }

            $inventory->quantity -= $quantity;
            $inventory->save();

            $product->sold_count += $quantity;
            $product->save();

            $itemTotal = floatval($product->price) * $quantity;
            $subtotal = $itemTotal;
            $shippingCity = $request->input('shipping_city', 'Dhaka');
            if ($subtotal >= 5000) {
                $shippingCost = 0;
            } elseif (strtolower($shippingCity) === 'dhaka') {
                $shippingCost = 80;
            } else {
                $shippingCost = 120;
            }
            $total = $subtotal + $shippingCost;

            $orderNumber = 'HI-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $userId,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'shipping_name' => $request->input('shipping_name'),
                'shipping_phone' => $request->input('shipping_phone'),
                'shipping_address' => $request->input('shipping_address'),
                'shipping_city' => $shippingCity,
                'notes' => $request->input('notes')
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'total_price' => $itemTotal
            ]);

            Payment::create([
                'order_id' => $order->id,
                'method' => $request->input('payment_method'),
                'status' => 'pending',
                'amount' => $total
            ]);

            // Record sale debit in the inventory ledger (stock was already deducted above)
            InventoryLedger::create([
                'product_id' => $product->id,
                'type' => 'sale',
                'quantity' => -$quantity,
                'balance_after' => Inventory::where('product_id', $product->id)->value('quantity') ?? 0,
                'reference_type' => 'order',
                'reference_id' => $order->id,
                'note' => 'Sold with order ' . $orderNumber,
                'user_id' => $order->user_id,
            ]);

            DB::commit();

            Log::info("Quick order placed successfully: " . $order->order_number);

            // Send confirmation emails AFTER commit, non-blocking (runs after the response is sent)
            $this->dispatchOrderEmails($order, $product, $quantity, $total, $orderNumber);

            return $this->orderSuccessResponse($request, $orderNumber);

            } catch (Exception $e) {
                DB::rollBack();
                Log::error("Quick checkout failed: " . $e->getMessage());
                return $this->orderErrorResponse($request, 'Order failed: ' . $e->getMessage());
            }
        } catch (ValidationException $e) {
            $messages = collect($e->errors())->flatten()->unique()->implode(' ');
            return $this->orderErrorResponse($request, 'Please correct the form: ' . $messages);
        }
    }

    // --- Order success page ---
    public function orderSuccess($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->first();

        $products = Product::where('is_active', true)
            ->where('id', '!=', $order ? $order->items()->value('product_id') : null)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        if ($products->count() < 8) {
            $featured = Product::where('is_active', true)->where('is_featured', true)->take(8)->get();
            $products = $products->merge($featured)->unique('id')->take(8);
        }

        return view('pages.order-success', [
            'title' => 'Order Confirmed — HomeI Cozy Living',
            'order' => $order,
            'products' => $products
        ]);
    }

    // --- Shared: order success/error responses for HTMX forms ---
    private function orderSuccessResponse(Request $request, string $orderNumber)
    {
        $url = '/order/success/' . $orderNumber;

        if ($request->headers->has('hx-request')) {
            return response('')->header('HX-Redirect', $url);
        }

        return redirect($url);
    }

    private function orderErrorResponse(Request $request, string $message)
    {
        $html = '<div class="alert alert-danger" style="padding:1rem;border-radius:10px;background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5;margin-bottom:1rem;font-size:0.9rem;">'
            . '<strong>We could not place your order.</strong><br>' . e($message)
            . '<br><button type="button" class="btn btn-secondary btn-sm" style="margin-top:0.75rem;" onclick="var c=document.getElementById(\'cart-drawer-close\'); if(c){c.click();}else{location.reload();}">Try Again</button>'
            . '</div>';

        if ($request->headers->has('hx-request')) {
            return response($html, 200);
        }

        return back()->with('error', $message);
    }

    // --- Email helper methods ---
    private function sendOrderEmails(
        object $order,
        object $product,
        int $quantity,
        float $total,
        string $orderNumber
    ): void
    {
        $user = User::find($order->user_id);

        $template = EmailTemplate::getActive();

        if ($user && !empty($user->email)) {
            $subject = $template
                ? Blade::render($template->subject, ['order' => $order, 'user' => $user])
                : 'Order Confirmation — Order #' . $order->order_number;

            $htmlBody = $template
                ? Blade::render($template->body_html, ['order' => $order, 'user' => $user])
                : '<p>Thank you for your order #' . $order->order_number . '</p>';

            Mail::send([], [], function ($message) use ($user, $subject, $htmlBody) {
                $message->to($user->email)
                        ->subject($subject)
                        ->html($htmlBody)
                        ->from('shop@homeibd.com', 'HomeI');
            });

            Mail::send('emails.admin-order-notification', [
                'user' => $user,
                'order' => $order,
                'product' => $product,
                'quantity' => $quantity,
                'total' => $total
            ], function ($message) use ($order) {
                $message->to('homeibd26@gmail.com')
                        ->bcc('homeibd26@gmail.com')
                        ->subject('New Order Received - HomeI - Order #' . $order->order_number)
                        ->from('shop@homeibd.com', 'HomeI');
            });
        } else {
            $guestPhone = $user ? $user->phone : '';
            if (!empty($guestPhone)) {
                Mail::send('emails.guest-order-notification', [
                    'user' => $user,
                    'order' => $order,
                    'product' => $product,
                    'quantity' => $quantity,
                    'total' => $total
                ], function ($message) use ($order) {
                    $message->to('homeibd26@gmail.com')
                            ->subject('New Guest Order Received - HomeI - Order #' . $order->order_number)
                            ->from('shop@homeibd.com', 'HomeI');
                });
            }
        }
    }

    // --- Dispatch order confirmation emails after the response is sent (non-blocking) ---
    private function dispatchOrderEmails(object $order, object $product, int $quantity, float $total, string $orderNumber): void
    {
        try {
            dispatch(function () use ($order, $product, $quantity, $total, $orderNumber) {
                $this->sendOrderEmails($order, $product, $quantity, $total, $orderNumber);
            })->afterResponse();
        } catch (Throwable $e) {
            Log::warning("Could not queue order confirmation emails for order " . $orderNumber . ": " . $e->getMessage());
        }
    }

    // --- Additional methods (productDetail, etc.) ---
    public function productDetail($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        $relatedProducts = Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $images = [$product->image];
        if ($product->images && is_array($product->images)) {
            $images = array_merge($images, $product->images);
        }

        return view('pages.product', [
            'title' => $product->name . ' — HomeI Cozy Living',
            'product' => $product,
            'categories' => $categories,
            'relatedProducts' => $relatedProducts,
            'images' => $images
        ]);
    }
}