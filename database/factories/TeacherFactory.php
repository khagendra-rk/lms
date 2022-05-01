<?php

namespace Database\Factories;

use App\Models\User;
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
        $user = User::create([
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'password' => bcrypt('password'),
            'role_id' => '4',
        ]);

        return [
            'name' => $user->name,
            'email' => $user->email,
            'user_id' => $user->id,
            'college_email' => $this->faker->safeEmail(),
            'phone_no' => mt_rand(9800000000, 9999999999),
            'address' => $this->faker->city(),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
