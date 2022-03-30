<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Index>
 */
class IndexFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $book_ids = Book::all()->pluck('prefix', 'id')->toArray();

        $total = count($book_ids);
        $id = mt_rand(1, $total - 1);
        $code = $book_ids[$id];

        return [
            'book_id' => $id,
            'book_prefix' => $code,
            'code' => $this->faker->unique()->numberBetween(100, 1000),
            'is_borrowed' => $this->faker->boolean(),
        ];
    }
}
