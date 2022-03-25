<?php

namespace Database\Factories;

use App\Models\Index;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrow>
 */
class BorrowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $index_count = Index::count();

        $arr = [
            'issued_by' => "Librarian",
            'index_id' => mt_rand(1, $index_count),
            'issued_at' => $this->faker->dateTimeBetween('-1 months', '+1 days'),
            'returned_at' => mt_rand(0, 1) ? null : $this->faker->dateTimeBetween('+2 days', '+3 months'),
        ];

        if (mt_rand(0, 1)) {
            $student_count = Student::count();
            $arr['student_id'] = mt_rand(1, $student_count - 1);
        } else {
            $teacher_count = Teacher::count();
            $arr['teacher_id'] = mt_rand(1, $teacher_count - 1);
        }

        return $arr;
    }
}
