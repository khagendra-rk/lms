<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'author' => $this->faker->name(),
            'publication' => $this->faker->word(3),
            'published_year' => $this->faker->year(),
            'price' => $this->faker->randomFloat(2, 300, 1000),
            'book_type' => $this->faker->randomElement(['textbook', 'blackbook', 'other']),
            'prefix' => strtoupper(Str::random(3)),
            'edition' => mt_rand(1, 10),
            'added_by' => $this->faker->randomElement(['Admin', 'Librarian']),
        ];
    }
}
