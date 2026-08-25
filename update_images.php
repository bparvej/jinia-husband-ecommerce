<?php

$files = [
    'resources/views/pages/product.blade.php',
    'resources/views/pages/order-success.blade.php',
    'resources/views/pages/home.blade.php',
    'resources/views/pages/checkout.blade.php',
    'resources/views/pages/category.blade.php',
    'resources/views/partials/cart-drawer-content.blade.php',
    'resources/views/admin/categories/partials/category-table.blade.php',
    'resources/views/admin/orders/detail.blade.php',
    'resources/views/admin/categories/edit.blade.php',
    'resources/views/admin/inventory/partials/ledger-table.blade.php',
    'resources/views/admin/inventory/partials/inventory-table.blade.php',
    'resources/views/admin/dashboard.blade.php',
    'resources/views/admin/products/partials/product-table.blade.php',
    'resources/views/admin/products/edit.blade.php',
    'resources/views/admin/settings/banner.blade.php'
];

$replacements = [
    "{{ \$entry->product ? (\$entry->product->image ?? '/assets/images/category-bookshelf.png') : '/assets/images/category-bookshelf.png' }}" => "{{ !empty(\$entry->product) && !empty(\$entry->product->image) ? asset('storage/' . \$entry->product->image) : '/assets/images/category-bookshelf.png' }}",
    "{{ \$item->product ? (\$item->product->image ?? '/assets/images/category-bookshelf.png') : '/assets/images/category-bookshelf.png' }}" => "{{ !empty(\$item->product) && !empty(\$item->product->image) ? asset('storage/' . \$item->product->image) : '/assets/images/category-bookshelf.png' }}",
    "{{ \$item->product ? \$item->product->image ?? '/assets/images/category-bookshelf.png' : '/assets/images/category-bookshelf.png' }}" => "{{ !empty(\$item->product) && !empty(\$item->product->image) ? asset('storage/' . \$item->product->image) : '/assets/images/category-bookshelf.png' }}",
    "{{ \$images[0] ?? '/assets/images/category-bookshelf.png' }}" => "{{ !empty(\$images[0]) ? asset('storage/' . \$images[0]) : '/assets/images/category-bookshelf.png' }}",
    "{{ \$rp->image ?? '/assets/images/category-bookshelf.png' }}" => "{{ !empty(\$rp->image) ? asset('storage/' . \$rp->image) : '/assets/images/category-bookshelf.png' }}",
    "{{ \$product->image ?? '/assets/images/category-bookshelf.png' }}" => "{{ !empty(\$product->image) ? asset('storage/' . \$product->image) : '/assets/images/category-bookshelf.png' }}",
    "{{ \$bannerImage ?? '/assets/images/hero-living-room.png' }}" => "{{ !empty(\$bannerImage) ? asset('storage/' . \$bannerImage) : '/assets/images/hero-living-room.png' }}",
    "{{ \$cat->image ?? '/assets/images/category-bookshelf.png' }}" => "{{ !empty(\$cat->image) ? asset('storage/' . \$cat->image) : '/assets/images/category-bookshelf.png' }}",
    "{{ \$item->Product->image ?? '/assets/images/category-bookshelf.png' }}" => "{{ !empty(\$item->Product->image) ? asset('storage/' . \$item->Product->image) : '/assets/images/category-bookshelf.png' }}",
    "{{ \$item->product->image ?? '/assets/images/category-bookshelf.png' }}" => "{{ !empty(\$item->product->image) ? asset('storage/' . \$item->product->image) : '/assets/images/category-bookshelf.png' }}",
    "{{ \$img }}" => "{{ asset('storage/' . \$img) }}",
    "{{ \$cat->image }}" => "{{ asset('storage/' . \$cat->image) }}",
    "{{ \$product->image }}" => "{{ asset('storage/' . \$product->image) }}",
    "{{ \$category->image }}" => "{{ asset('storage/' . \$category->image) }}",
    "{{ \$bannerImage }}" => "{{ asset('storage/' . \$bannerImage) }}"
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    
    $content = file_get_contents($file);
    
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    file_put_contents($file, $content);
    echo "Processed $file\n";
}
