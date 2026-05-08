<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(10),
            'price' => fake()->randomFloat(2, 0.99, 999.99),
            'quantity_available' => fake()->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the product is in stock.
     */
    public function inStock(): static
    {
        return $this->state(fn (array $attributes) => array_merge($attributes, [
            'quantity_available' => fake()->numberBetween(1, 100),
        ]));
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => array_merge($attributes, [
            'quantity_available' => 0,
        ]));
    }

    /**
     * Indicate that the product has low stock (less than 5).
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => array_merge($attributes, [
            'quantity_available' => fake()->numberBetween(1, 4),
        ]));
    }

    /**
     * Create a product with specific price.
     */
    public function price(float $price): static
    {
        return $this->state(fn (array $attributes) => array_merge($attributes, [
            'price' => $price,
        ]));
    }

    /**
     * Create a product with specific quantity.
     */
    public function quantity(int $quantity): static
    {
        return $this->state(fn (array $attributes) => array_merge($attributes, [
            'quantity_available' => $quantity,
        ]));
    }
}
