<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategori_id' => fake()->numberBetween(1, 7),
            'barcode' => fake()->ean13(),
            'produk' => fake()->sentence(3),
            'keterangan' => fake()->sentence(7),
            'stok' => 99,
            
            'stok_warning' => fake()->numberBetween(0, 5),
            'unit_id' => fake()->numberBetween(1, 2),
        ];
    }
}
