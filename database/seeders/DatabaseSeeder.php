<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            SchoolSeeder::class,
        ]);

        AdminUser::firstOrCreate(
            ['email' => 'admin@atrio.com.br'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('admin123'),
            ]
        );
    }
}