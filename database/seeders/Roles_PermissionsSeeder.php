<?php

namespace Database\Seeders;

use App\Models\Roles_Permissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Roles_PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Roles_Permissions::factory(10)->create();
    }
}
