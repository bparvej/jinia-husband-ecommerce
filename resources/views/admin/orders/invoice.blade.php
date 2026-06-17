<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Outfit', -apple-system, sans-serif;
            background: #F8F5F0;
            color: #2C1810;
            padding: 40px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .invoice-wrapper {
            max-width: 900px;
            margin: 0 auto;
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(44,24,16,0.1);
            overflow: hidden;
        }
        .invoice-header {
            background: #2C1810;
            padding: 40px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .invoice-brand .brand-name {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: 3px;
            color: #FFFFFF;
            display: block;
        }
        .invoice-brand .brand-tagline {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.4);
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 2rem;
            font-weight: 700;
            color: #C4956A;
            letter-spacing: 2px;
        }
        .invoice-title p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.5);
        }
        .invoice-body { padding: 40px 50px; }
        .invoice-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 35px;
            padding-bottom: 25px;
            border-bottom: 2px dashed #EDE6DB;
        }
        .meta-block h3 {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #9B8C80;
            margin-bottom: 8px;
        }
        .meta-block .name { font-size: 1rem; font-weight: 600; color: #2C1810; }
        .meta-block .detail { font-size: 0.88rem; color: #6B5B4E; line-height: 1.6; }
        .meta-block .detail strong { color: #2C1810; }
        .status-badge-invoice {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .status-badge-invoice.pending { background: #FFF7ED; color: #D97706; }
        .status-badge-invoice.confirmed { background: #EFF6FF; color: #2563EB; }
        .status-badge-invoice.processing { background: #F0FDF4; color: #16A34A; }
        .status-badge-invoice.shipped { background: #FDF2F8; color: #DB2777; }
        .status-badge-invoice.delivered { background: #ECFDF5; color: #059669; }
        .status-badge-invoice.cancelled { background: #FEF2F2; color: #DC2626; }
        .status-badge-invoice.refunded { background: #F5F5F4; color: #78716C; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        thead th {
            text-align: left;
            padding: 12px 16px;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #9B8C80;
            background: #FAF6F1;
            border-bottom: 2px solid #EDE6DB;
        }
        thead th:last-child { text-align: right; }
        tbody td {
            padding: 14px 16px;
            font-size: 0.88rem;
            color: #2C1810;
            border-bottom: 1px solid #F3ECE2;
        }
        tbody td:last-child {
            text-align: right;
            font-weight: 600;
        }
        tfoot td {
            padding: 10px 16px;
            font-size: 0.88rem;
            color: #6B5B4E;
        }
        tfoot td:last-child {
            text-align: right;
            font-weight: 600;
            color: #2C1810;
        }
        tfoot tr.total-row td {
            font-size: 1.05rem;
            font-weight: 700;
            color: #2C1810;
            border-top: 2px solid #EDE6DB;
            padding-top: 14px;
        }
        .invoice-footer {
            border-top: 2px dashed #EDE6DB;
            padding-top: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .invoice-footer p {
            font-size: 0.8rem;
            color: #9B8C80;
        }
        .invoice-footer .payment-note {
            font-size: 0.82rem;
            color: #6B5B4E;
        }
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 14px 28px;
            background: #5C3D2E;
            color: #fff;
            border: none;
            border-radius: 50px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(92,61,46,0.3);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .print-btn:hover {
            background: #7A5640;
            transform: translateY(-2px);
        }
        @media print {
            body { padding: 0; background: #fff; }
            .invoice-wrapper { box-shadow: none; border-radius: 0; }
            .print-btn { display: none; }
            .invoice-header { background: #2C1810 !important; }
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper">
        <div class="invoice-header">
            <div class="invoice-brand">
                <span class="brand-name">HOMEI</span>
                <span class="brand-tagline">Cozy Living</span>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <p>#{{ $order->order_number }}</p>
            </div>
        </div>

        <div class="invoice-body">
            <div class="invoice-meta">
                <div class="meta-block">
                    <h3>Bill To</h3>
                    <p class="name">{{ $order->shipping_name ?? $order->user->name ?? 'N/A' }}</p>
                    <p class="detail">
                        {{ $order->shipping_phone ?? $order->user->phone ?? '' }}<br>
                        {{ $order->shipping_address ?? '' }}<br>
                        {{ $order->shipping_city ?? '' }}
                    </p>
                </div>
                <div class="meta-block" style="text-align:right">
                    <h3>Invoice Details</h3>
                    <p class="detail">
                        <strong>Date:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}<br>
                        <strong>Status:</strong> <span class="status-badge-invoice {{ $order->status }}">{{ ucfirst($order->status) }}</span><br>
                        <strong>Payment:</strong> {{ $order->payment ? strtoupper($order->payment->method) : 'N/A' }}
                    </p>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width:50%">Item</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th style="text-align:right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>BDT {{ number_format($item->unit_price) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>BDT {{ number_format($item->total_price) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Subtotal</td>
                        <td>BDT {{ number_format($order->subtotal) }}</td>
                    </tr>
                    @if ($order->discount_amount > 0)
                    <tr>
                        <td colspan="3">Discount</td>
                        <td style="color:#059669">-BDT {{ number_format($order->discount_amount) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="3">Shipping</td>
                        <td>{{ $order->shipping_cost > 0 ? 'BDT ' . number_format($order->shipping_cost) : 'Free' }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3">Total</td>
                        <td>BDT {{ number_format($order->total) }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="invoice-footer">
                <p>Thank you for choosing <strong>HOMEI</strong></p>
                <p class="payment-note">
                    @if ($order->payment && $order->payment->method === 'cod')
                        Payment method: Cash on Delivery
                    @elseif ($order->payment)
                        Payment: {{ strtoupper($order->payment->method) }} {{ $order->payment->status === 'completed' ? '(Paid)' : '(Pending)' }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <button class="print-btn" onclick="window.print()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Print / Save PDF
    </button>
</body>
</html>