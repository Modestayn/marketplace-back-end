<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create main categories
        $mainCategories = $this->createMainCategories();

        // Create subcategories for each main category
        $allCategories = $mainCategories;
        foreach ($mainCategories as $category) {
            $subcategories = $this->createSubcategories($category->id);
            $allCategories = array_merge($allCategories, $subcategories);
        }

        // Create 100 products assigned to random categories
        $this->createProducts($allCategories);
    }

    /**
     * Create main categories
     *
     * @return array
     */
    private function createMainCategories(): array
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and accessories',
                'icon' => 'laptop',
                'is_featured' => true,
            ],
            [
                'name' => 'Home & Kitchen',
                'description' => 'Products for your home',
                'icon' => 'home',
                'is_featured' => true,
            ],
            [
                'name' => 'Clothing',
                'description' => 'Apparel and fashion items',
                'icon' => 'shirt',
                'is_featured' => true,
            ],
            [
                'name' => 'Sports & Outdoors',
                'description' => 'Equipment for sports and outdoor activities',
                'icon' => 'activity',
                'is_featured' => false,
            ],
            [
                'name' => 'Books',
                'description' => 'Books across various genres',
                'icon' => 'book',
                'is_featured' => false,
            ],
        ];

        $createdCategories = [];
        $order = 0;

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'icon' => $categoryData['icon'],
                'image_url' => 'https://picsum.photos/seed/' . Str::slug($categoryData['name']) . '/800/600',
                'parent_id' => null,
                'order' => $order++,
                'is_active' => true,
                'is_featured' => $categoryData['is_featured'],
            ]);

            $createdCategories[] = $category;
        }

        return $createdCategories;
    }

    /**
     * Create subcategories for a parent category
     *
     * @param int $parentId
     * @return array
     */
    private function createSubcategories(int $parentId): array
    {
        $parent = Category::find($parentId);
        $subcategories = [];

        // Create different subcategories based on parent category
        switch ($parent->name) {
            case 'Electronics':
                $subcategories = ['Smartphones', 'Laptops', 'Tablets', 'Wearables', 'Accessories'];
                break;
            case 'Home & Kitchen':
                $subcategories = ['Furniture', 'Appliances', 'Cookware', 'Decor', 'Storage'];
                break;
            case 'Clothing':
                $subcategories = ['Men', 'Women', 'Kids', 'Shoes', 'Accessories'];
                break;
            case 'Sports & Outdoors':
                $subcategories = ['Fitness', 'Camping', 'Water Sports', 'Team Sports', 'Winter Sports'];
                break;
            case 'Books':
                $subcategories = ['Fiction', 'Non-Fiction', 'Children', 'Academic', 'Self-Help'];
                break;
        }

        $createdSubcategories = [];
        $order = 0;

        foreach ($subcategories as $subcategory) {
            $category = Category::create([
                'name' => $subcategory,
                'slug' => Str::slug($parent->name . '-' . $subcategory),
                'description' => $subcategory . ' in the ' . $parent->name . ' category',
                'icon' => strtolower(substr($subcategory, 0, 1)),
                'image_url' => 'https://picsum.photos/seed/' . Str::slug($parent->name . '-' . $subcategory) . '/800/600',
                'parent_id' => $parentId,
                'order' => $order++,
                'is_active' => true,
                'is_featured' => false,
            ]);

            $createdSubcategories[] = $category;
        }

        return $createdSubcategories;
    }

    /**
     * Create products and assign them to categories
     *
     * @param array $categories
     * @return void
     */
    private function createProducts(array $categories): void
    {
        $productNames = [
            'Electronics' => [
                'iPhone 15 Pro', 'Samsung Galaxy S24', 'MacBook Air', 'Dell XPS 13', 'iPad Pro',
                'Surface Pro', 'AirPods Pro', 'Galaxy Watch', 'Apple Watch', 'Fitbit Versa',
                'Sony WH-1000XM5', 'Bose QuietComfort', 'Pixel 8', 'Nintendo Switch', 'Kindle Paperwhite'
            ],
            'Home & Kitchen' => [
                'Instant Pot', 'KitchenAid Mixer', 'Dyson V12', 'Nespresso Vertuo', 'Ninja Blender',
                'iRobot Roomba', 'Air Fryer Pro', 'Keurig Coffee Maker', 'Le Creuset Dutch Oven', 'Vitamix Blender',
                'Crock-Pot Slow Cooker', 'Shark Vacuum', 'Cuisinart Food Processor', 'Breville Toaster Oven', 'SodaStream'
            ],
            'Clothing' => [
                'Levi\'s 501 Jeans', 'Nike Air Max', 'Adidas Ultraboost', 'North Face Jacket', 'Patagonia Fleece',
                'Ray-Ban Sunglasses', 'Hanes T-Shirt', 'Under Armour Shorts', 'Columbia Jacket', 'Vans Sneakers',
                'Champion Hoodie', 'Ralph Lauren Polo', 'Calvin Klein Underwear', 'Lululemon Leggings', 'Timberland Boots'
            ],
            'Sports & Outdoors' => [
                'Yeti Cooler', 'Coleman Tent', 'Hydro Flask', 'Garmin GPS Watch', 'Wilson Basketball',
                'Spalding Football', 'Callaway Golf Clubs', 'Schwinn Bicycle', 'Lifetime Kayak', 'NordicTrack Treadmill',
                'Bowflex Dumbbells', 'Camping Hammock', 'Fishing Rod Set', 'Ski Goggles', 'Hiking Backpack'
            ],
            'Books' => [
                'Atomic Habits', 'The Alchemist', 'Dune', 'Project Hail Mary', 'The Silent Patient',
                'Where the Crawdads Sing', 'Educated', 'Sapiens', 'The Psychology of Money', 'The Midnight Library',
                'The Four Winds', 'The Hobbit', 'Harry Potter Collection', 'The 48 Laws of Power', 'Rich Dad Poor Dad'
            ]
        ];

        $descriptions = [
            'Top-rated product with excellent customer reviews.',
            'Premium quality product that exceeds expectations.',
            'Affordable option without compromising on quality.',
            'Bestselling product in its category.',
            'Limited edition with exclusive features.',
            'New arrival with cutting-edge technology.',
            'Eco-friendly and sustainable choice.',
            'Customer favorite for over a decade.',
            'Handcrafted with attention to detail.',
            'Professional grade for serious users.',
        ];

        // Create 100 products
        for ($i = 0; $i < 100; $i++) {
            // Select a random category
            $category = $categories[array_rand($categories)];

            // Find the parent category if it's a subcategory
            $parentCategory = $category->parent_id ? Category::find($category->parent_id) : $category;

            // Get product names based on the parent category
            $namesForThisCategory = $productNames[$parentCategory->name] ?? $productNames['Electronics'];

            // Generate a product name
            $productName = $namesForThisCategory[array_rand($namesForThisCategory)];

            // Create the product
            Product::create([
                'name' => $productName,
                'description' => $descriptions[array_rand($descriptions)],
                'image_url' => 'https://picsum.photos/seed/' . Str::slug($productName) . '/400/400',
                'category_id' => $category->id,
                'price' => fake()->randomFloat(2, 9.99, 999.99),
                'is_available' => fake()->boolean(80), // 80% will be available
            ]);
        }
    }
}
