<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Parent</th>
                <th>Products</th>
                <th>Sort</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $cat)
            <tr>
                <td>
                    <div class="cell-with-image">
                        @if ($cat->image)
                        <img src="{{ !empty($cat->image) ? asset('storage/' . $cat->image) : '' }}" alt="" class="cell-img-sm">
                        @endif
                        <span class="cell-title">{{ $cat->name }}</span>
                    </div>
                </td>
                <td><code class="cell-code">{{ $cat->slug }}</code></td>
                <td>{{ $cat->parent ? $cat->parent->name : '—' }}</td>
                <td><span class="cell-count">{{ $cat->products_count }}</span></td>
                <td><span class="cell-badge">{{ $cat->sort_order }}</span></td>
                <td>
                    @if ($cat->is_active)
                    <span class="status-badge status-active">Active</span>
                    @else
                    <span class="status-badge status-inactive">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="action-btns">
                        <a href="/admin/categories/{{ $cat->id }}/edit" class="action-btn-sm edit" title="Edit">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        <form method="POST" action="/admin/categories/{{ $cat->id }}" style="display:inline"
                            onsubmit="return confirm('Delete {{ $cat->name }}? Products will be uncategorized.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn-sm delete" title="Delete">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="empty-row">
                    <div class="empty-state">
                        <span class="empty-icon">📂</span>
                        <p>No categories yet</p>
                        <a href="/admin/categories/create" class="btn-admin btn-primary-admin">Create your first category</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($categories->hasPages())
<div class="pagination">
    {{ $categories->appends(request()->query())->links() }}
</div>
@endif
