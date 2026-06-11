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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (count($products) > 0)
                    @foreach ($products as $product)
                    <tr>
                        <td>
                            <div class="product-cell">
                                <img src="{{ $product->image ?? '/assets/images/category-bookshelf.png' }}" alt="{{ $product->name }}" class="product-thumb">
                                <div>
                                    <span class="product-cell-name">{{ $product->name }}</span>
                                    @if ($product->badge)
                                        <span class="badge-sm badge-{{ str_replace(' ', '-', strtolower($product->badge)) }}">{{ $product->badge }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-mono">{{ $product->sku ?? '—' }}</td>
                        <td>{{ $product->category ? $product->category->name : '—' }}</td>
                        <td>
                            <span class="text-bold">৳{{ number_format($product->price) }}</span>
                            @if ($product->compare_price)
                                <br><span class="text-muted text-sm line-through">৳{{ number_format($product->compare_price) }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($product->inventory)
                                <span class="stock-badge {{ $product->inventory->quantity <= $product->inventory->low_stock_threshold ? ($product->inventory->quantity <= 5 ? 'critical' : 'low') : 'ok' }}">
                                    {{ $product->inventory->quantity }}
                                </span>
                            @else
                                <span class="stock-badge critical">0</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-dot {{ $product->is_active ? 'active' : 'inactive' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="/admin/products/{{ $product->id }}/edit" class="action-btn-sm edit" title="Edit">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>
                                <button class="action-btn-sm delete" title="Delete"
                                    hx-delete="/admin/products/{{ $product->id }}"
                                    hx-confirm="Delete '{{ $product->name }}'? This action cannot be undone."
                                    hx-target="closest tr" hx-swap="outerHTML swap:0.3s">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="7" class="text-center text-muted">No products found</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    @if ($products->hasPages())
    <div class="pagination">
        @if ($products->onFirstPage())
            <span class="page-btn disabled">← Prev</span>
        @else
            <a href="{{ $products->previousPageUrl() }}" class="page-btn" hx-get="/admin/products?page={{ $products->currentPage() - 1 }}&_partial=1" hx-target="#product-table-container">← Prev</a>
        @endif
        @for ($i = 1; $i <= $products->lastPage(); $i++)
            <a href="{{ $products->url($i) }}" class="page-btn {{ $i === $products->currentPage() ? 'active' : '' }}"
                hx-get="/admin/products?page={{ $i }}&_partial=1" hx-target="#product-table-container">{{ $i }}</a>
        @endfor
        @if ($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}" class="page-btn" hx-get="/admin/products?page={{ $products->currentPage() + 1 }}&_partial=1" hx-target="#product-table-container">Next →</a>
        @else
            <span class="page-btn disabled">Next →</span>
        @endif
    </div>
    @endif
</div>
