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

class StorefrontController extends Controller
{
    public function home()
    {
        $products = Product::where('is_active', true)->orderBy('created_at', 'desc')->take(8)->get();
        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $featuredProducts = Product::where('is_active', true)->where('is_featured', true)->take(8)->get();

        $totalProducts = Product::where('is_active', true)->count();
        $totalOrders = \App\Models\Order::where('status', 'delivered')->count();
        $avgRating = Product::where('is_active', true)->where('avg_rating', '>', 0)->avg('avg_rating');

        return view('pages.home', [
            'title' => 'HomeI — Cozy Living | Wooden & Home Decor Furniture',
            'products' => $products,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'avgRating' => round($avgRating, 1)
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

    public function getCartDrawer(Request $request)
    {
        $cartData = $this->getCartData($request);
        return view('partials.cart-drawer-content', [
            'cart' => $cartData['cart'],
            'count' => $cartData['count'],
            'csrfToken' => csrf_token()
        ]);
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = intval($request->input('quantity', 1));

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
            if (!$found) {
                $cartItems[] = [
                    'product_id' => intval($productId),
                    'quantity' => $quantity
                ];
            }
            $request->session()->put('cart.items', $cartItems);
        }

        return $this->getCartDrawer($request);
    }

    public function updateCartQuantity(Request $request, $productId)
    {
        $quantity = intval($request->input('quantity', 1));
        if ($quantity < 1) {
            return $this->removeFromCart($request, $productId);
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
            $request->session()->put('cart.items', $cartItems);
        }

        return $this->getCartDrawer($request);
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

        return $this->getCartDrawer($request);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:100',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'payment_method' => 'required|string'
        ]);

        $cartData = $this->getCartData($request);
        $cartItems = $cartData['cart']->CartItems;

        if (count($cartItems) === 0) {
            return response('<div class="toast toast-error">Your cart is empty</div>', 400);
        }

        DB::beginTransaction();

        try {
            $userId = Auth::id();

            // Handle Guest Registration/Lookup
            if (!$userId) {
                $shippingPhone = $request->input('shipping_phone');
                $user = User::where('phone', $shippingPhone)->first();
                
                if (!$user) {
                    $cleanPhone = preg_replace('/\s+/', '', $shippingPhone);
                    $guestEmail = "guest_{$cleanPhone}@homei.com.bd";
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
                }
                $userId = $user->id;
            }

            // Deduct stock, verify inventory, calculate subtotal
            $subtotal = 0;
            $orderItemsData = [];

            foreach ($cartItems as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if (!$product) {
                    throw new \Exception("Product not found: " . $item->product_id);
                }

                $inventory = Inventory::where('product_id', $product->id)->lockForUpdate()->first();
                if (!$inventory || $inventory->quantity < $item->quantity) {
                    throw new \Exception("Insufficient stock for " . $product->name);
                }

                // Deduct stock
                $inventory->quantity -= $item->quantity;
                $inventory->save();

                // Increment sold count
                $product->sold_count += $item->quantity;
                $product->save();

                $itemTotal = floatval($product->price) * $item->quantity;
                $subtotal += $itemTotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $product->price,
                    'total_price' => $itemTotal
                ];
            }

            $shippingCost = $subtotal >= 5000 ? 0 : 200;
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
                'shipping_city' => $request->input('shipping_city'),
                'notes' => $request->input('notes')
            ]);

            // Save order items
            foreach ($orderItemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'method' => $request->input('payment_method'),
                'status' => 'pending',
                'amount' => $total
            ]);

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

            return view('partials.checkout-success', [
                'order' => $order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Checkout transaction failed: " . $e->getMessage());
            return response('<div class="toast toast-error">Checkout failed: ' . $e->getMessage() . '</div>', 400);
        }
    }

    // --- Product Detail Page ---
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
                $user = User::where('phone', $shippingPhone)->first();

                if (!$user) {
                    $cleanPhone = preg_replace('/\s+/', '', $shippingPhone);
                    $guestEmail = "guest_{$cleanPhone}@homei.com.bd";
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
                }
                $userId = $user->id;
            }

            $product = Product::lockForUpdate()->find($product->id);
            $inventory = Inventory::where('product_id', $product->id)->lockForUpdate()->first();

            if (!$inventory || $inventory->quantity < $quantity) {
                throw new \Exception("Insufficient stock for " . $product->name);
            }

            $inventory->quantity -= $quantity;
            $inventory->save();

            $product->sold_count += $quantity;
            $product->save();

            $itemTotal = floatval($product->price) * $quantity;
            $subtotal = $itemTotal;
            $shippingCost = $subtotal >= 5000 ? 0 : 200;
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
                'shipping_city' => $request->input('shipping_city'),
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

            // Send order confirmation emails
            $this->sendOrderEmails($order, $product, $quantity, $total, $orderNumber);

            DB::commit();

            Log::info("Quick order placed successfully: " . $order->order_number);

            return view('partials.checkout-success', [
                'order' => $order
            ]);

        } catch (\\Exception $e) {
            DB::rollBack();
            Log::error("Quick checkout failed: " . $e->getMessage());
            return response('<div class="toast toast-error">Checkout failed: ' . $e->getMessage() . '</div>', 400);
        }
    }

    // --- Email helper methods ---
    private function sendOrderEmail($userId, $order, $userName, $shippingPhone, $shippingAddress, $shippingCity)
    {
        $user = User::find($userId);
        if ($user && !empty($user->email)) {
            Mail::send('emails.order-confirmation', [
                'user' => $user,
                'order' => $order,
                'orderNumber' => $order ? $order->order_number : null
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Order Confirmation - HomeI - Order #' . ($order ? $order->order_number : ''))
                        ->from('shop@homeibd.com', 'HomeI');
            });
        }
    }

    private function sendOrderNotificationEmail($userName, $total)
    {
        Mail::send('emails.order-notification', [
            'userName' => $userName,
            'total' => $total
        ], function ($message) {
            $message->to('homeibd26@gmail.com')
                    ->cc('homeibd26@gmail.com')
                    ->subject('New Order Notification - HomeI')
                    ->from('shop@homeibd.com', 'HomeI');
        });
    }

    private function sendOrderEmails($order, $product, $quantity, $total, $orderNumber)
    {
        $user = User::find($order->user_id);
        if ($user && !empty($user->email)) {
            // Send customer order confirmation
            Mail::send('emails.order-confirmation', [
                'user' => $user,
                'order' => $order
            ], function ($message) use ($user, $order) {
                $message->to($user->email)
                        ->subject('Order Confirmation - HomeI - Order #' . $order->order_number)
                        ->from('shop@homeibd.com', 'HomeI');
            });

            // Send admin notification with order details
            Mail::send('emails.admin-order-notification', [
                'user' => $user,
                'order' => $order,
                'product' => $product,
                'quantity' => $quantity,
                'total' => $total
            ], function ($message) use ($order) {
                $message->to('homeibd26@gmail.com')
                        ->bcc('homeibd26@gmail.com')
                        ->subject('🚚 New Order Received - HomeI - Order #' . $order->order_number)
                        ->from('shop@homeibd.com', 'HomeI');
            });
        } else {
            // Guest user (email comes from cart)
            $guestEmail = 'guest_' . ($user ? $user->phone : '') . '@homei.com.bd';
            if (isset($user->phone) && !empty($user->phone)) {
                Mail::send('emails.guest-order-notification', [
                    'user' => $user,
                    'order' => $order,
                    'product' => $product,
                    'quantity' => $quantity,
                    'total' => $total
                ], function ($message) use ($order, $guestEmail) {
                    $message->to('homeibd26@gmail.com')
                            ->subject('🚚 New Guest Order Received - HomeI - Order #' . $order->order_number)
                            ->from('shop@homeibd.com', 'HomeI');
                });
            }
        }
    }

            DB::commit();

            Log::info("Quick order placed successfully: " . $order->order_number);

            return view('partials.checkout-success', [
                'order' => $order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Quick checkout failed: " . $e->getMessage());
            return response('<div class="toast toast-error">Checkout failed: ' . $e->getMessage() . '</div>', 400);
        }
    }

    // --- Helper to aggregate session & DB cart ---
    private function getCartData(Request $request)
    {
        $cart = new \stdClass();
        $cart->CartItems = collect();
        $count = 0;

        if (Auth::check()) {
            $user = Auth::user();
            $dbCart = Cart::with(['items.product'])->where('user_id', $user->id)->first();
            if ($dbCart) {
                foreach ($dbCart->items as $item) {
                    if ($item->product) {
                        $cartItem = new \stdClass();
                        $cartItem->id = $item->id;
                        $cartItem->product_id = $item->product_id;
                        $cartItem->quantity = $item->quantity;
                        $cartItem->Product = $item->product;
                        $cart->CartItems->push($cartItem);
                        $count += $item->quantity;
                    }
                }
            }
        } else {
            $sessionItems = $request->session()->get('cart.items', []);
            if (count($sessionItems) > 0) {
                $productIds = array_column($sessionItems, 'product_id');
                $products = Product::whereIn('id', $productIds)->where('is_active', true)->get()->keyBy('id');

                foreach ($sessionItems as $item) {
                    if (isset($products[$item['product_id']])) {
                        $cartItem = new \stdClass();
                        $cartItem->id = 'guest-' . $item['product_id'];
                        $cartItem->product_id = $item['product_id'];
                        $cartItem->quantity = $item['quantity'];
                        $cartItem->Product = $products[$item['product_id']];
                        $cart->CartItems->push($cartItem);
                        $count += $item['quantity'];
                    }
                }
            }
        }

        return ['cart' => $cart, 'count' => $count];
    }
}
