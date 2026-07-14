@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h2>Categories</h2>
        <span class="page-count">{{ $categories->total() ?? 0 }} total</span>
    </div>
    <div>
        <a href="/admin/categories/create" class="btn-admin btn-primary-admin">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add New Category
        </a>
    </div>
</div>

<div class="filters-bar">
    <div class="filter-search">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" placeholder="Search categories..." value="{{ $filters['search'] ?? '' }}"
            hx-get="/admin/categories" hx-target="#category-table-container" hx-trigger="keyup changed delay:300ms" name="search">
    </div>
</div>

<div id="category-table-container">
    @include('admin.categories.partials.category-table')
</div>
@endsection
