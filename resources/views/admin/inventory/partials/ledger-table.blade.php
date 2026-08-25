@php
    $qs = http_build_query(array_filter([
        'search' => $filters['search'] ?? null,
        'type' => $filters['type'] ?? null,
        'date_from' => $filters['date_from'] ?? null,
        'date_to' => $filters['date_to'] ?? null,
    ]));
    $qs = $qs ? '&' . $qs : '';
@endphp

<div class="panel">
    <div class="panel-body">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Reference</th>
                    <th>Stock In (Credit)</th>
                    <th>Stock Out (Debit)</th>
                    <th>Balance</th>
                    <th>Updated By</th>
                </tr>
            </thead>
            <tbody>
                @if (count($ledger) > 0)
                    @foreach ($ledger as $entry)
                    <tr>
                        <td class="text-muted">{{ \Carbon\Carbon::parse($entry->created_at)->format('d M Y, h:i A') }}</td>
                        <td>
                            <div class="product-cell">
                                <img src="{{ !empty($entry->product) && !empty($entry->product->image) ? asset('storage/' . $entry->product->image) : '/assets/images/category-bookshelf.png' }}" alt="{{ $entry->product->name ?? 'Unknown Product' }}" class="product-thumb" onerror="this.onerror=null;this.src='/assets/images/category-bookshelf.png';">
                                <div>
                                    <span class="product-cell-name">{{ $entry->product->name ?? 'Unknown Product' }}</span>
                                    <span class="text-mono" style="display:block; font-size:0.75rem;">{{ $entry->product->sku ?? '—' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="role-badge {{ $entry->type }}">
                                {{ $entry->type_label }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $entry->note ?? '—' }}</span>
                        </td>
                        <td>
                            @if ($entry->quantity > 0)
                                <span class="status-badge active">+{{ $entry->quantity }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if ($entry->quantity < 0)
                                <span class="status-badge inactive">{{ $entry->quantity }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><strong>{{ $entry->balance_after }}</strong></td>
                        <td class="text-muted">{{ $entry->user->name ?? 'System' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="8" class="text-center text-muted">No ledger entries found</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    @if ($ledger->hasPages())
    <div class="pagination">
        @if ($ledger->onFirstPage())
            <span class="page-btn disabled">← Prev</span>
        @else
            <a hx-get="/admin/inventory/ledger?page={{ $ledger->currentPage() - 1 }}&_partial=true{{ $qs }}" hx-target="#ledger-table-container" class="page-btn">← Prev</a>
        @endif
        @for ($i = 1; $i <= $ledger->lastPage(); $i++)
            <a hx-get="/admin/inventory/ledger?page={{ $i }}&_partial=true{{ $qs }}" hx-target="#ledger-table-container" class="page-btn {{ $i === $ledger->currentPage() ? 'active' : '' }}">{{ $i }}</a>
        @endfor
        @if ($ledger->hasMorePages())
            <a hx-get="/admin/inventory/ledger?page={{ $ledger->currentPage() + 1 }}&_partial=true{{ $qs }}" hx-target="#ledger-table-container" class="page-btn">Next →</a>
        @else
            <span class="page-btn disabled">Next →</span>
        @endif
    </div>
    @endif
</div>
