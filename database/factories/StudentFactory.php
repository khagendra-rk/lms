<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Faculty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $count = Faculty::count();
        $id = mt_rand(1, $count - 1);

        $user = User::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'password' => bcrypt('password'),
            'role' => 'Teacher',
        ]);

        return [
            'name' => $user->name,
            'email' => $user->email,
            'user_id' => $user->id,
            'phone_no' => mt_rand(9800000000, 9999999999),
            'address' => $this->faker->city(),
            'parent_name' => $this->faker->name(),
            'parent_contact' => mt_rand(9800000000, 9999999999),
            'college_email' => $this->faker->safeEmail(),
            'faculty_id' => $id,
            'year' => $this->faker->year(),
            'image' => $this->faker->imageUrl(),
            'registration_no' => $this->faker->unique()->numberBetween(1, 200000),
            'symbol_no' => $this->faker->unique()->numberBetween(1, 150000),
        ];
    }
}
