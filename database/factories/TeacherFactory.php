<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
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
            'email' => $this->faker->safeEmail(),
            'college_email' => $this->faker->safeEmail(),
            'phone_no' => mt_rand(9800000000, 9999999999),
            'address' => $this->faker->city(),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
