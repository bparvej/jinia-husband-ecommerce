<div class="panel">
    <div class="panel-body">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                @if (count($inventory) > 0)
                    @foreach ($inventory as $item)
                    <tr x-data="{ qty: {{ $item->quantity }}, editing: false }">
                        <td>
                            <div class="product-cell">
                                <img src="{{ $item->product ? ($item->product->image ?? '/assets/images/category-bookshelf.png') : '/assets/images/category-bookshelf.png' }}" alt="{{ $item->product ? $item->product->name : 'Unknown Product' }}" class="product-thumb">
                                <span class="product-cell-name">{{ $item->product ? $item->product->name : 'Unknown Product' }}</span>
                            </div>
                        </td>
                        <td class="text-mono">{{ $item->product ? ($item->product->sku ?? '—') : '—' }}</td>
                        <td>{{ ($item->product && $item->product->category) ? $item->product->category->name : '—' }}</td>
                        <td>৳{{ number_format($item->product ? $item->product->price : 0) }}</td>
                        <td>
                            <template x-if="!editing">
                                <span class="stock-badge {{ $item->quantity <= $item->low_stock_threshold ? ($item->quantity <= 5 ? 'critical' : 'low') : 'ok' }}" @click="editing = true" style="cursor:pointer" title="Click to edit">
                                    <span x-text="qty"></span>
                                </span>
                            </template>
                            <template x-if="editing">
                                <input type="number" x-model="qty" class="inline-input" min="0" @keyup.enter="editing = false; $refs.submitBtn.click()" @keyup.escape="editing = false">
                            </template>
                        </td>
                        <td>
                            <span class="stock-indicator">
                                @if ($item->quantity <= 5)
                                    <span class="dot dot-critical"></span> Critical
                                @elseif ($item->quantity <= $item->low_stock_threshold)
                                    <span class="dot dot-warning"></span> Low Stock
                                @else
                                    <span class="dot dot-ok"></span> In Stock
                                @endif
                            </span>
                        </td>
                        <td>
                            <button x-ref="submitBtn" class="action-btn-sm save"
                                hx-put="/admin/inventory/{{ $item->product_id }}"
                                :hx-vals="JSON.stringify({ quantity: qty, _token: '{{ csrf_token() }}' })"
                                hx-swap="none"
                                @click="editing = false"
                                title="Save">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="7" class="text-center text-muted">No inventory found</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    @if ($inventory->hasPages())
    <div class="pagination">
        @if ($inventory->onFirstPage())
            <span class="page-btn disabled">← Prev</span>
        @else
            <a href="{{ $inventory->previousPageUrl() }}" class="page-btn">← Prev</a>
        @endif
        @for ($i = 1; $i <= $inventory->lastPage(); $i++)
            <a href="{{ $inventory->url($i) }}" class="page-btn {{ $i === $inventory->currentPage() ? 'active' : '' }}">{{ $i }}</a>
        @endfor
        @if ($inventory->hasMorePages())
            <a href="{{ $inventory->nextPageUrl() }}" class="page-btn">Next →</a>
        @else
            <span class="page-btn disabled">Next →</span>
        @endif
    </div>
    @endif
</div>
