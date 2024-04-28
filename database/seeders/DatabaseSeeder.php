<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                RolesSeeder::class,
                UserSeeder::class,
                CategoriesSeeder::class,
                TestsSeeder::class,
                QuestionSeeder::class,
                AnswerSeeder::class,
                OptionsSeeder::class,
                PermissionsSeeder::class,
                Roles_PermissionsSeeder::class,

            ]
        );
    }
}
