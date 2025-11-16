<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);

        Product::factory()->create([
            'title' => 'Gaming Laptop',
            'description' => 'High performance gaming laptop with RGB keyboard.',
            'price' => 129.99,
            'stock' => 25,
            'image_url' => 'https://placehold.co/400x400/000000/FFFFFF/png?text=LAP',
        ]);

        Product::factory()->create([
            'title' => 'Samsung s23',
            'description' => 'Latest flagship smartphone with amazing camera.',
            'price' => 99.59,
            'stock' => 40,
            'image_url' => 'https://placehold.co/400x400/000000/FFFFFF/png?text=S23',
        ]);

        Product::factory()->create([
            'title' => 'Iphone 17',
            'description' => 'Latest flagship smartphone with amazing chipset.',
            'price' => 99.59,
            'stock' => 40,
            'image_url' => 'https://placehold.co/400x400/000000/FFFFFF/png?text=ios',
        ]);
    }
}
