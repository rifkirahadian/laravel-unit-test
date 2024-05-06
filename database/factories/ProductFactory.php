<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomNumber(2),
            'is_show' => $this->faker->boolean ? 1 : 0,
            'category' => $this->faker->word,
            'deleted_at' => null
        ];
    }
}
