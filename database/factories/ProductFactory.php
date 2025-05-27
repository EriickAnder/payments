<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // 'uuid' => Uuid::uuid4(),
            'uuid' => 'ce3a4e01-451a-4397-a495-c0571b7b94c6',
            'name' => 'Camisa',
            'description' => 'Preta | Gola v',
            'price' => 49.90,
            'category_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
