<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use App\Models\Index;
use App\Models\Borrow;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Faculty::truncate();
        Book::truncate();
        Index::truncate();
        Student::truncate();
        Borrow::truncate();
        DB::table('book_faculty')->truncate();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'Admin',
        ]);

        User::create([
            'name' => 'Librarian',
            'email' => 'library@admin.com',
            'password' => bcrypt('password'),
            'role' => 'Librarian',
        ]);

        User::factory(100)->create();
        Faculty::factory(4)->create();
        $faculties = Faculty::all()->pluck('id')->toArray();
        Book::factory(150)->create()->each(function ($book) use ($faculties) {
            $faculty_ids = [];
            for ($i = 0; $i < rand(1, 2); $i++) {
                $faculty_ids[] = $faculties[count($faculties) - 1];
            }

            foreach ($faculty_ids as $fid) {
                $book->faculties()->attach($fid, ['semester' => rand(1, 8)]);
            }
        });
        Index::factory(70)->create();
        Student::factory(100)->create();
        Teacher::factory(20)->create();
        Borrow::factory(200)->create();
    }
}
