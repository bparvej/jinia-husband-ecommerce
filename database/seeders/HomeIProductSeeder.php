<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Inventory;

class HomeIProductSeeder extends Seeder
{
    public function run()
    {
        // ============================
        // 1. CATEGORIES
        // ============================
        $lightingCat = Category::firstOrCreate(
            ['slug' => 'lighting'],
            ['name' => 'Lighting', 'description' => 'Floor lamps, table lamps, and ambient lighting', 'sort_order' => 7, 'is_active' => true]
        );

        $shelvingCat = Category::firstOrCreate(
            ['slug' => 'shelving'],
            ['name' => 'Shelving', 'description' => 'Floating shelves, wall shelves, and display units', 'sort_order' => 8, 'is_active' => true]
        );

        // ============================
        // 2. PRODUCTS
        // ============================
        $products = [
            [
                'name' => 'HOMEI Driftwood Bamboo Floor Lamp',
                'slug' => 'homei-driftwood-bamboo-floor-lamp',
                'description' => 'Bring nature into your space—effortlessly. This one-of-a-kind floor lamp is crafted from original tamarind driftwood, shaped by time and refined by hand. Paired with a handwoven bamboo shade, it casts a warm, calming glow that instantly transforms any room into a cozy, aesthetic retreat. No two pieces are ever the same—each lamp carries its own story.

Authentic Tamarind Driftwood – naturally sculpted, truly unique
Handcrafted Bamboo Shade (12" round) – soft, warm light diffusion
Edison Round Bulb Included – vintage glow, ready to use
Solid Metal Base – stable, durable, premium feel
Perfect for living rooms, bedrooms, cafés & cozy corners

Total Height: ~36 inches
Shade Diameter: 12 inches (round)
Stand Material: Natural Tamarind Driftwood
Shade Material: Handwoven Bamboo
Base: Heavy-duty Metal
Bulb Type: Round Edison Bulb (included)
Light Tone: Warm ambient glow',
                'short_description' => 'Handcrafted tamarind driftwood floor lamp with handwoven bamboo shade',
                'price' => 6590,
                'compare_price' => null,
                'cost_price' => 3800,
                'sku' => 'HI-LT-001',
                'category_id' => $lightingCat->id,
                'image' => '/uploads/products/homei-driftwood-bamboo-floor-lamp.png',
                'badge' => 'New',
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'HOMEI Driftwood Glow Table Lamp',
                'slug' => 'homei-driftwood-glow-table-lamp',
                'description' => 'Bring a touch of nature into your home with the HOMEI Driftwood Glow Table Lamp. Crafted from real driftwood with a warm burnished finish, each piece carries its own unique character—no two lamps are ever the same. Paired with a vintage-style Edison bulb, it creates a soft, cozy ambiance perfect for modern, minimalist, or rustic interiors.

Technical Specifications:
Total Height: Approx. 16 inches
Base Diameter: Approx. 1 inch
Material: Natural driftwood (burnished finish)
Bulb Type: Round Edison bulb (included)
Lighting Style: Warm ambient glow
Setup: Ready to use (fully assembled)
Power Source: Standard plug-in

Perfect for home office desks, bedside tables, living room accent lighting.',
                'short_description' => 'Natural driftwood table lamp with warm Edison bulb glow',
                'price' => 2900,
                'compare_price' => null,
                'cost_price' => 1600,
                'sku' => 'HI-LT-002',
                'category_id' => $lightingCat->id,
                'image' => '/uploads/products/homei-driftwood-glow-table-lamp.png',
                'badge' => 'Best Seller',
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'HOMEI Floral Wood Table Lamp',
                'slug' => 'homei-floral-wood-table-lamp',
                'description' => 'A soft glow. A touch of color. A feeling of home. This beautifully handcrafted table lamp blends natural wood warmth with a vibrant floral shade, creating a perfect balance between cozy and lively. Whether it\'s your bedside, study table, or a quiet corner—this lamp instantly lifts the mood of your space.

Features:
Elegant Floral Shade – adds color, charm & personality
Solid Wooden Base & Stand – warm, natural aesthetic
Soft Ambient Lighting – perfect for relaxing evenings
Ideal for bedroom, study, or cozy corners
A statement piece even when turned off

Technical Details:
Total Height: 13 inches
Shade Diameter: 7 inches
Stand Height: 5 inches
Base Thickness: 0.6 inches
Material (Base & Stand): Solid Wood
Shade: Printed Fabric (Floral Pattern)
Light Type: Warm ambient',
                'short_description' => 'Handcrafted table lamp with floral fabric shade and solid wood base',
                'price' => 1590,
                'compare_price' => 1990,
                'cost_price' => 900,
                'sku' => 'HI-LT-003',
                'category_id' => $lightingCat->id,
                'image' => '/uploads/products/homei-floral-wood-table-lamp.png',
                'badge' => 'Sale',
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'HOMEI Industrial Wood Beam Table Lamp',
                'slug' => 'homei-industrial-wood-beam-table-lamp',
                'description' => 'Where raw materials meet refined design. This statement lamp blends solid reclaimed wood with minimal metal detailing to create a bold, architectural look. The exposed Edison bulb hangs effortlessly within the frame, casting a warm, ambient glow—perfect for modern desks, studios, or cozy workspaces.

Features:
Solid Reclaimed Wood – rich texture, natural character
Minimal Metal Frame – clean, modern industrial aesthetic
Exposed Edison Bulb – warm, vintage glow
Perfect for desks, studios & creative spaces
A statement piece that elevates your setup instantly

Material: Solid Wood + Metal Rod Structure
Bulb Type: Edison Filament Bulb
Lighting Tone: Warm Ambient
Style: Industrial / Minimal / Rustic Modern

Includes: Wooden Base & Top Beam Structure, Metal Rod Frame, Edison Bulb, pre-installed wiring (plug & play).',
                'short_description' => 'Industrial reclaimed wood table lamp with metal frame and Edison bulb',
                'price' => 3500,
                'compare_price' => null,
                'cost_price' => 2000,
                'sku' => 'HI-LT-004',
                'category_id' => $lightingCat->id,
                'image' => '/uploads/products/homei-industrial-wood-beam-table-lamp.png',
                'badge' => null,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'HOMEI Solid Mehogoni Floating Shelves (Set of 3)',
                'slug' => 'homei-solid-mehogoni-floating-shelves-set-of-3',
                'description' => 'Crafted from authentic mehogoni wood, these floating shelves bring warmth, depth, and timeless elegance to your walls. Each piece carries natural grain patterns, making every shelf uniquely yours. Designed for modern homes, these shelves create a clean, minimal "floating" look while adding functional beauty to your space.

Key Features:
Premium solid mehogoni wood (not MDF or veneer)
Natural wood grain – every piece is unique
Clean floating design (hidden support system)
Smooth hand-finished surface with light burnish
Strong & durable for daily use

Technical Specifications:
Width: 16 inches
Depth: 6 inches
Thickness: 1.5 inches
Material: 100% Solid Mehogoni Wood
Finish: Natural wood tone with light burnish

Includes: 3 × Solid wood shelves, 3 × Heavy-duty hidden metal brackets, wall plugs, screws, installation guide.

Weight Capacity: Approx. 8–12 kg per shelf.',
                'short_description' => '3-piece solid mehogoni wood floating shelf set with hidden brackets',
                'price' => 2450,
                'compare_price' => 3200,
                'cost_price' => 1400,
                'sku' => 'HI-SH-001',
                'category_id' => $shelvingCat->id,
                'image' => '/uploads/products/homei-solid-mehogoni-floating-shelves-set-of-3.png',
                'badge' => 'Best Seller',
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'HOMEI Floating Shelves 5ps Set',
                'slug' => 'homei-floating-shelves-5ps-set',
                'description' => 'Crafted from authentic mehogoni wood, this 5-piece floating shelf set brings warmth, balance, and modern elegance to your walls. Designed in a mix of sizes, the arrangement creates a visually dynamic layout—perfect for showcasing plants, frames, and décor while maintaining a clean, minimal aesthetic.

Key Features:
Premium solid mehogoni wood (no MDF or veneer)
Thoughtfully designed 5-piece size variation for aesthetic balance
Clean floating design (hidden bracket system)
Smooth hand-finished surface with warm burnish
Strong, durable & long-lasting

Technical Specifications:
Shelf Sizes: 2 × 20 inches, 1 × 16 inches, 2 × 12 inches
Depth: 6 inches
Thickness: 1.5 inches
Material: 100% Solid Mehogoni Wood
Finish: Natural wood tone with light burnish

Includes: 5 × Solid wood shelves, 5 × Heavy-duty hidden metal brackets, wall plugs, screws, installation guide.

Weight Capacity: Approx. 8–12 kg per shelf.',
                'short_description' => '5-piece mixed-size solid mehogoni floating shelf set with hidden brackets',
                'price' => 3850,
                'compare_price' => 4800,
                'cost_price' => 2200,
                'sku' => 'HI-SH-002',
                'category_id' => $shelvingCat->id,
                'image' => '/uploads/products/homei-floating-shelves-5ps-set.png',
                'badge' => 'Hot',
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'HOMEI Floating Plant Styling Edition (Set of 5)',
                'slug' => 'homei-floating-plant-styling-edition-set-of-5',
                'description' => 'Bring life to your walls with the HOMEI Floating Mini Shelf Set—perfectly designed for plant lovers and minimalist homes. Crafted from premium mehogoni wood, these compact shelves create a stunning vertical garden effect, ideal for showcasing trailing greens like golden pothos and other indoor plants.

With their clean floating design and warm natural finish, these shelves turn any empty corner into a fresh, calming, and Instagram-worthy space.

Key Features:
Premium solid mehogoni wood (no MDF or veneer)
Compact, uniform size—perfect for plant styling
Elegant floating design (hidden bracket system)
Smooth hand-finished surface with natural burnish
Strong, durable & space-saving

Technical Specifications:
Shelf Size (Each): 12 inches (width)
Depth: 6 inches
Thickness: 1.5 inches
Material: 100% Solid Mehogoni Wood
Finish: Natural wood tone with light burnish

Includes: 5 × Solid wood shelves, 5 × Heavy-duty hidden metal brackets, wall plugs, screws, installation guide.

Weight Capacity: Approx. 6–10 kg per shelf.',
                'short_description' => '5-piece mini floating shelf set for plant styling, solid mehogoni wood',
                'price' => 2950,
                'compare_price' => null,
                'cost_price' => 1700,
                'sku' => 'HI-SH-003',
                'category_id' => $shelvingCat->id,
                'image' => '/uploads/products/homei-floating-plant-styling-edition-set-of-5.png',
                'badge' => 'New',
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $data) {
            $product = Product::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            Inventory::firstOrCreate(
                ['product_id' => $product->id],
                [
                    'quantity' => 10,
                    'low_stock_threshold' => 5,
                    'warehouse_location' => 'Dhaka Main',
                ]
            );

            $this->command->info("Added: {$data['name']} — {$data['price']}৳ (stock: 10)");
        }

        $this->command->info('HomeI Product Seeding Complete!');
    }
}
