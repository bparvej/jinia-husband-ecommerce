<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'compare_price',
        'cost_price',
        'sku',
        'category_id',
        'image',
        'images',
        'badge',
        'is_active',
        'is_featured',
        'avg_rating',
        'review_count',
        'sold_count',
    ];

    protected $casts = [
        'price' => 'float',
        'compare_price' => 'float',
        'cost_price' => 'float',
        'images' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'avg_rating' => 'float',
        'review_count' => 'integer',
        'sold_count' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function validateProductData(array $data = [], string $operation = 'store'): array
    {
        $errors = [];
        
        // Sanitize product name
        $cleanName = trim(strip_tags($data['name'] ?? ''));
        if (empty($cleanName)) {
            $errors['name'][] = 'Product name is required';
        } elseif (strlen($cleanName) < 3) {
            $errors['name'][] = 'Product name must be at least 3 characters';
        } elseif (strlen($cleanName) > 255) {
            $errors['name'][] = 'Product name cannot exceed 255 characters';
        } elseif (preg_match('/^(test_|dummy_|sample_|invalid_|lorem ipsum|placeholder|unspecified)/i', $cleanName)) {
            $errors['name'][] = 'Product name appears to be test or placeholder data';
        }
        
        // Validate slug if provided
        if (!empty($data['slug'])) {
            $cleanSlug = $this->cleanSlug($data['slug']);
            if (strlen($cleanSlug) < 2 || strlen($cleanSlug) > 280) {
                $errors['slug'][] = 'Slug must be between 2 and 280 characters';
            } elseif (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $cleanSlug)) {
                $errors['slug'][] = 'Slug must be lowercase letters, numbers, and hyphens separated';
            } elseif (Product::where('slug', $cleanSlug)->where('id', '!=', $data['id'] ?? null)->exists()) {
                $errors['slug'][] = 'Slug is already in use';
            }
        }
        
        // Sanitize and validate description
        $cleanDescription = trim(strip_tags($data['description'] ?? ''));
        if ($cleanDescription && strlen($cleanDescription) > 65535) {
            $errors['description'][] = 'Description cannot exceed 65,535 characters';
        } elseif (!empty($data['description']) && strlen($cleanDescription) < 10 && !isset($data['force_short'])) {
            $errors['description'][] = 'Description must be at least 10 characters if provided';
        }
        
        // Sanitize and validate short_description
        $cleanShortDescription = trim(strip_tags($data['short_description'] ?? ''));
        if ($cleanShortDescription && strlen($cleanShortDescription) > 500) {
            $errors['short_description'][] = 'Short description cannot exceed 500 characters';
        } elseif (!empty($data['short_description']) && strlen($cleanShortDescription) < 10) {
            $errors['short_description'][] = 'Short description must be at least 10 characters if provided';
        }
        
        // Sanitize and validate prices
        if (isset($data['price'])) {
            $cleanPrice = floatval($data['price']);
            if ($cleanPrice < 0) {
                $errors['price'][] = 'Price cannot be negative';
            } elseif ($cleanPrice > 999999999.99) {
                $errors['price'][] = 'Price cannot exceed ৳999,999,999.99';
            } elseif (in_array($cleanPrice, [0.00, 0.01, 0.99])) {
                $errors['price'][] = 'Price appears to be a test or placeholder value';
            }
        }
        
        return $errors;
    }

    public function cleanSlug(string $slug): string
    {
        // Convert to lowercase
        $slug = strtolower($slug);
        // Replace spaces with hyphens
        $slug = preg_replace('/\s+/', '-', $slug);
        // Remove special characters except hyphens
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
        // Remove multiple consecutive hyphens
        $slug = preg_replace('/-+/', '-', $slug);
        // Remove leading/trailing hyphens
        $slug = trim($slug, '-');
        
        // Return original if very short but valid
        if (strlen($slug) < 2 && preg_match('/^[a-z]$/i', $data['name'] ?? '')) {
            $slug = 'product-' . $slug;
        }
        
        return $slug;
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
