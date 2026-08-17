<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $classicHtml = <<<'HTML'
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4;padding:40px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

<!-- Header -->
<tr><td style="background-color:#1a1a2e;padding:30px 40px;text-align:center;">
<h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;letter-spacing:1px;">HOMEI</h1>
<p style="margin:6px 0 0;color:#a0a0b8;font-size:12px;letter-spacing:2px;text-transform:uppercase;">Cozy Living</p>
</td></tr>

<!-- Success Badge -->
<tr><td style="padding:35px 40px 10px;text-align:center;">
<div style="width:64px;height:64px;border-radius:50%;background-color:#d4edda;margin:0 auto 15px;line-height:64px;font-size:28px;">&#10003;</div>
<h2 style="margin:0;color:#1a1a2e;font-size:22px;">Order Confirmed!</h2>
<p style="margin:8px 0 0;color:#6c757d;font-size:14px;">Thank you for shopping with HomeI.</p>
</td></tr>

<!-- Order Number -->
<tr><td style="padding:15px 40px;text-align:center;">
<table width="100%" cellpadding="0" cellspacing="0"><tr>
<td style="background-color:#f8f9fa;border-radius:6px;padding:12px 20px;text-align:center;">
<span style="color:#6c757d;font-size:13px;">Order Number</span><br>
<strong style="color:#1a1a2e;font-size:18px;letter-spacing:1px;">{{ $order->order_number }}</strong>
</td>
</tr></table>
</td></tr>

<!-- Items -->
<tr><td style="padding:25px 40px 10px;">
<h3 style="margin:0 0 12px;color:#1a1a2e;font-size:16px;border-bottom:1px solid #eee;padding-bottom:8px;">Order Items</h3>
<table width="100%" cellpadding="0" cellspacing="0">
<tr style="background-color:#f8f9fa;">
<td style="padding:8px 12px;font-size:12px;color:#6c757d;font-weight:600;text-transform:uppercase;">Product</td>
<td style="padding:8px 12px;font-size:12px;color:#6c757d;font-weight:600;text-align:center;">Qty</td>
<td style="padding:8px 12px;font-size:12px;color:#6c757d;font-weight:600;text-align:right;">Price</td>
</tr>
@foreach($order->items as $item)
<tr style="border-bottom:1px solid #f0f0f0;">
<td style="padding:12px;font-size:14px;color:#333;">{{ $item->product_name }}</td>
<td style="padding:12px;font-size:14px;color:#6c757d;text-align:center;">{{ $item->quantity }}</td>
<td style="padding:12px;font-size:14px;color:#333;text-align:right;">৳{{ number_format($item->total_price, 2) }}</td>
</tr>
@endforeach
</table>
</td></tr>

<!-- Totals -->
<tr><td style="padding:10px 40px 25px;">
<table width="100%" cellpadding="0" cellspacing="0">
<tr><td style="padding:6px 0;font-size:14px;color:#6c757d;">Subtotal</td>
<td style="padding:6px 0;font-size:14px;color:#333;text-align:right;">৳{{ number_format($order->subtotal, 2) }}</td></tr>
<tr><td style="padding:6px 0;font-size:14px;color:#6c757d;">Shipping</td>
<td style="padding:6px 0;font-size:14px;color:#333;text-align:right;">{{ $order->shipping_cost > 0 ? '৳'.number_format($order->shipping_cost, 2) : 'FREE' }}</td></tr>
<tr><td style="padding:10px 0 0;font-size:16px;font-weight:700;color:#1a1a2e;border-top:2px solid #1a1a2e;">Total</td>
<td style="padding:10px 0 0;font-size:16px;font-weight:700;color:#1a1a2e;text-align:right;border-top:2px solid #1a1a2e;">৳{{ number_format($order->total, 2) }}</td></tr>
</table>
</td></tr>

<!-- Shipping -->
<tr><td style="padding:0 40px 25px;">
<div style="background-color:#f8f9fa;border-radius:6px;padding:16px 20px;">
<h3 style="margin:0 0 8px;color:#1a1a2e;font-size:14px;">Shipping Details</h3>
<p style="margin:0;color:#6c757d;font-size:13px;line-height:1.6;">
{{ $order->shipping_name }}<br>
{{ $order->shipping_address }}, {{ $order->shipping_city }}<br>
Phone: {{ $order->shipping_phone }}
</p>
</div>
</td></tr>

<!-- Footer -->
<tr><td style="background-color:#f8f9fa;padding:25px 40px;text-align:center;border-top:1px solid #eee;">
<p style="margin:0;color:#999;font-size:12px;">If you have any questions, contact us at <a href="mailto:shop@homeibd.com" style="color:#1a1a2e;">shop@homeibd.com</a></p>
<p style="margin:10px 0 0;color:#bbb;font-size:11px;">&copy; {{ date('Y') }} HomeI — Cozy Living. All rights reserved.</p>
</td></tr>

</table>
</td></tr></table>
</body></html>
HTML;

        $modernHtml = <<<'HTML'
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;background-color:#0f0f0f;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0f0f0f;padding:40px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background-color:#1a1a2e;border-radius:16px;overflow:hidden;">

<!-- Gradient Header -->
<tr><td style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:40px;text-align:center;">
<p style="margin:0;color:rgba(255,255,255,0.8);font-size:13px;letter-spacing:3px;text-transform:uppercase;">New Order</p>
<h1 style="margin:10px 0 0;color:#ffffff;font-size:32px;font-weight:800;">HOMEI</h1>
</td></tr>

<!-- Success -->
<tr><td style="padding:35px 40px;text-align:center;">
<div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);margin:0 auto 20px;line-height:72px;font-size:32px;color:#fff;">&#10003;</div>
<h2 style="margin:0;color:#ffffff;font-size:24px;">Order Placed!</h2>
<p style="margin:10px 0 0;color:#8888aa;font-size:14px;">We're preparing your order with care.</p>
</td></tr>

<!-- Order Badge -->
<tr><td style="padding:0 40px 25px;">
<div style="background-color:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:16px 24px;text-align:center;">
<span style="color:#8888aa;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Order ID</span><br>
<span style="color:#ffffff;font-size:20px;font-weight:700;letter-spacing:2px;">{{ $order->order_number }}</span>
</div>
</td></tr>

<!-- Items -->
<tr><td style="padding:0 40px 25px;">
<h3 style="margin:0 0 15px;color:#ffffff;font-size:15px;text-transform:uppercase;letter-spacing:1px;">Items Ordered</h3>
@foreach($order->items as $item)
<div style="background-color:rgba(255,255,255,0.05);border-radius:10px;padding:14px 18px;margin-bottom:10px;">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td style="font-size:14px;color:#ffffff;font-weight:600;">{{ $item->product_name }}</td>
<td style="font-size:14px;color:#ffffff;text-align:right;font-weight:700;">৳{{ number_format($item->total_price, 2) }}</td>
</tr>
<tr>
<td style="font-size:12px;color:#8888aa;padding-top:4px;">Qty: {{ $item->quantity }} &times; ৳{{ number_format($item->unit_price, 2) }}</td>
<td></td>
</tr>
</table>
</div>
@endforeach
</td></tr>

<!-- Total -->
<tr><td style="padding:0 40px 25px;">
<div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:15px;">
<table width="100%" cellpadding="0" cellspacing="0">
<tr><td style="font-size:13px;color:#8888aa;padding:4px 0;">Subtotal</td>
<td style="font-size:13px;color:#ffffff;text-align:right;padding:4px 0;">৳{{ number_format($order->subtotal, 2) }}</td></tr>
<tr><td style="font-size:13px;color:#8888aa;padding:4px 0;">Shipping</td>
<td style="font-size:13px;color:#ffffff;text-align:right;padding:4px 0;">{{ $order->shipping_cost > 0 ? '৳'.number_format($order->shipping_cost, 2) : 'FREE' }}</td></tr>
<tr><td style="font-size:18px;color:#ffffff;font-weight:800;padding:10px 0 0;">Total</td>
<td style="font-size:18px;color:#ffffff;font-weight:800;text-align:right;padding:10px 0 0;">৳{{ number_format($order->total, 2) }}</td></tr>
</table>
</div>
</td></tr>

<!-- Shipping -->
<tr><td style="padding:0 40px 30px;">
<div style="background:linear-gradient(135deg,rgba(102,126,234,0.15) 0%,rgba(118,75,162,0.15) 100%);border-radius:12px;padding:18px 22px;">
<h3 style="margin:0 0 8px;color:#ffffff;font-size:14px;">Delivering To</h3>
<p style="margin:0;color:#8888aa;font-size:13px;line-height:1.6;">
{{ $order->shipping_name }}<br>
{{ $order->shipping_address }}, {{ $order->shipping_city }}<br>
{{ $order->shipping_phone }}
</p>
</div>
</td></tr>

<!-- Footer -->
<tr><td style="padding:20px 40px;text-align:center;border-top:1px solid rgba(255,255,255,0.05);">
<p style="margin:0;color:#555;font-size:12px;">Questions? Email <a href="mailto:shop@homeibd.com" style="color:#667eea;">shop@homeibd.com</a></p>
<p style="margin:8px 0 0;color:#333;font-size:11px;">&copy; {{ date('Y') }} HomeI</p>
</td></tr>

</table>
</td></tr></table>
</body></html>
HTML;

        $minimalHtml = <<<'HTML'
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;background-color:#ffffff;font-family:Georgia,'Times New Roman',serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#ffffff;">
<tr><td align="center" style="padding:60px 20px;">
<table width="560" cellpadding="0" cellspacing="0">

<!-- Logo -->
<tr><td style="text-align:center;padding-bottom:40px;">
<h1 style="margin:0;color:#222;font-size:28px;font-weight:400;letter-spacing:4px;">HOMEI</h1>
</td></tr>

<!-- Divider -->
<tr><td style="border-top:1px solid #ddd;"></td></tr>

<!-- Content -->
<tr><td style="padding:40px 0 30px;">
<p style="margin:0 0 5px;color:#999;font-size:12px;text-transform:uppercase;letter-spacing:2px;">Confirmation</p>
<h2 style="margin:0 0 15px;color:#222;font-size:22px;font-weight:400;">Your order has been received.</h2>
<p style="margin:0;color:#666;font-size:15px;line-height:1.7;">
Dear {{ $user->name }},<br><br>
Thank you for your order. We are processing it now and will notify you when it ships.
</p>
</td></tr>

<!-- Order Reference -->
<tr><td style="padding:0 0 30px;">
<table width="100%" cellpadding="0" cellspacing="0">
<tr><td style="border-top:1px solid #eee;padding:15px 0;"><span style="color:#999;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Order Reference</span></td></tr>
<tr><td style="padding:0 0 15px;"><span style="color:#222;font-size:16px;">{{ $order->order_number }}</span></td></tr>
</table>
</td></tr>

<!-- Items -->
<tr><td style="padding:0 0 30px;">
<table width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #eee;">
<tr><td style="padding:15px 0 10px;color:#999;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Items</td></tr>
@foreach($order->items as $item)
<tr><td style="padding:8px 0;border-bottom:1px solid #f5f5f5;">
<table width="100%" cellpadding="0" cellspacing="0"><tr>
<td style="font-size:15px;color:#222;">{{ $item->product_name }}<span style="color:#999;font-size:13px;"> &times;{{ $item->quantity }}</span></td>
<td style="font-size:15px;color:#222;text-align:right;">৳{{ number_format($item->total_price, 2) }}</td>
</tr></table>
</td></tr>
@endforeach
</table>
</td></tr>

<!-- Total -->
<tr><td style="padding:0 0 30px;">
<table width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #ddd;">
<tr><td style="padding:15px 0 8px;color:#222;font-size:18px;">Total</td>
<td style="padding:15px 0 8px;color:#222;font-size:18px;text-align:right;">৳{{ number_format($order->total, 2) }}</td></tr>
</table>
</td></tr>

<!-- Shipping -->
<tr><td style="padding:0 0 40px;">
<p style="margin:0;color:#999;font-size:12px;text-transform:uppercase;letter-spacing:1px;">Shipping to</p>
<p style="margin:6px 0 0;color:#444;font-size:14px;line-height:1.6;">
{{ $order->shipping_name }}<br>
{{ $order->shipping_address }}, {{ $order->shipping_city }}<br>
{{ $order->shipping_phone }}
</p>
</td></tr>

<!-- Divider -->
<tr><td style="border-top:1px solid #ddd;"></td></tr>

<!-- Footer -->
<tr><td style="padding:30px 0;text-align:center;">
<p style="margin:0;color:#bbb;font-size:12px;">Questions? <a href="mailto:shop@homeibd.com" style="color:#666;">shop@homeibd.com</a></p>
<p style="margin:8px 0 0;color:#ccc;font-size:11px;">&copy; {{ date('Y') }} HomeI</p>
</td></tr>

</table>
</td></tr></table>
</body></html>
HTML;

        EmailTemplate::updateOrCreate(
            ['name' => 'classic'],
            [
                'label' => 'Classic',
                'subject' => 'Order Confirmation — Order #{{ $order->order_number }}',
                'body_html' => $classicHtml,
                'is_default' => true,
                'is_active' => true,
            ]
        );

        EmailTemplate::updateOrCreate(
            ['name' => 'modern'],
            [
                'label' => 'Modern',
                'subject' => 'Your Order #{{ $order->order_number }} is Confirmed',
                'body_html' => $modernHtml,
                'is_default' => false,
                'is_active' => false,
            ]
        );

        EmailTemplate::updateOrCreate(
            ['name' => 'minimal'],
            [
                'label' => 'Minimal',
                'subject' => 'Order Received — {{ $order->order_number }}',
                'body_html' => $minimalHtml,
                'is_default' => false,
                'is_active' => false,
            ]
        );
    }
}
