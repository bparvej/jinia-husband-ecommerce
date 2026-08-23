<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4;padding:40px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

<!-- Header -->
<tr><td style="background-color:#1a1a2e;padding:25px 40px;">
<p style="margin:0;color:#ffffff;font-size:18px;font-weight:700;">New Guest Order</p>
<p style="margin:4px 0 0;color:#a0a0b8;font-size:12px;">HOMEI Admin Notification</p>
</td></tr>

<!-- Alert -->
<tr><td style="padding:30px 40px 10px;">
<div style="background-color:#d1ecf1;border:1px solid #bee5eb;border-radius:8px;padding:14px 18px;">
<strong style="color:#0c5460;font-size:14px;">A guest customer has placed an order.</strong>
</div>
</td></tr>

<!-- Order Info -->
<tr><td style="padding:20px 40px;">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td style="padding:8px 0;"><span style="color:#6c757d;font-size:13px;">Order Number:</span></td>
<td style="padding:8px 0;text-align:right;"><strong style="color:#1a1a2e;font-size:14px;">{{ $order->order_number }}</strong></td>
</tr>
<tr>
<td style="padding:8px 0;"><span style="color:#6c757d;font-size:13px;">Guest Name:</span></td>
<td style="padding:8px 0;text-align:right;"><span style="color:#333;font-size:14px;">{{ $user->name ?? 'N/A' }}</span></td>
</tr>
<tr>
<td style="padding:8px 0;"><span style="color:#6c757d;font-size:13px;">Phone:</span></td>
<td style="padding:8px 0;text-align:right;"><span style="color:#333;font-size:14px;">{{ $order->shipping_phone }}</span></td>
</tr>
<tr>
<td style="padding:8px 0;"><span style="color:#6c757d;font-size:13px;">Payment Method:</span></td>
<td style="padding:8px 0;text-align:right;"><span style="color:#333;font-size:14px;">{{ ucfirst($order->payment->method ?? 'N/A') }}</span></td>
</tr>
</table>
</td></tr>

<!-- Product -->
<tr><td style="padding:0 40px 20px;">
<div style="background-color:#f8f9fa;border-radius:6px;padding:14px 18px;">
<p style="margin:0 0 4px;color:#6c757d;font-size:11px;text-transform:uppercase;letter-spacing:1px;">Product Ordered</p>
<p style="margin:0;color:#1a1a2e;font-size:14px;font-weight:600;">{{ $product->name }}</p>
<p style="margin:4px 0 0;color:#6c757d;font-size:13px;">Qty: {{ $quantity }}</p>
</div>
</td></tr>

<!-- Total -->
<tr><td style="padding:0 40px 25px;">
<table width="100%" cellpadding="0" cellspacing="0" style="border-top:2px solid #1a1a2e;padding-top:12px;">
<tr>
<td style="padding:10px 0 0;"><span style="color:#1a1a2e;font-size:18px;font-weight:700;">Order Total</span></td>
<td style="padding:10px 0 0;text-align:right;"><span style="color:#1a1a2e;font-size:18px;font-weight:700;">৳{{ number_format($total, 2) }}</span></td>
</tr>
</table>
</td></tr>

<!-- Shipping -->
<tr><td style="padding:0 40px 25px;">
<div style="background-color:#f8f9fa;border-radius:6px;padding:14px 18px;">
<p style="margin:0 0 6px;color:#1a1a2e;font-size:13px;font-weight:600;">Shipping Address</p>
<p style="margin:0;color:#6c757d;font-size:13px;line-height:1.6;">
{{ $order->shipping_name }}<br>
{{ $order->shipping_address }}, {{ $order->shipping_city }}
</p>
</div>
</td></tr>

<!-- Footer -->
<tr><td style="background-color:#f8f9fa;padding:20px 40px;text-align:center;border-top:1px solid #eee;">
<p style="margin:0;color:#999;font-size:12px;">This is an automated notification from HomeI E-commerce System</p>
</td></tr>

</table>
</td></tr></table>
</body></html>
