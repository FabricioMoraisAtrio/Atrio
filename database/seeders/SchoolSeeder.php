<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\User;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::firstOrCreate(
            ['slug' => 'escola-demo'],
            ['name' => 'Escola Demo', 'is_active' => true]
        );

        $secretaria = User::firstOrCreate(
            ['email' => 'secretaria@atrio.com.br'],
            [
                'name'      => 'Secretaria Demo',
                'password'  => bcrypt('password'),
                'school_id' => $school->id,
                'is_active' => true,
            ]
        );
        $secretaria->assignRole('secretaria');

        $professor = User::firstOrCreate(
            ['email' => 'professor@atrio.com.br'],
            [
                'name'      => 'Professor Demo',
                'password'  => bcrypt('password'),
                'school_id' => $school->id,
                'is_active' => true,
            ]
        );
        $professor->assignRole('professor');

        $pai = User::firstOrCreate(
            ['email' => 'pai@atrio.com.br'],
            [
                'name'      => 'Pai Demo',
                'password'  => bcrypt('password'),
                'school_id' => $school->id,
                'is_active' => true,
            ]
        );
        $pai->assignRole('pai');

        $coordenador = User::firstOrCreate(
            ['email' => 'coordenador@atrio.com.br'],
            [
                'name'      => 'Coordenador Demo',
                'password'  => bcrypt('password'),
                'school_id' => $school->id,
                'is_active' => true,
            ]
        );
        $coordenador->assignRole('coordenador');

        $orientador = User::firstOrCreate(
            ['email' => 'orientador@atrio.com.br'],
            [
                'name'      => 'Orientador Demo',
                'password'  => bcrypt('password'),
                'school_id' => $school->id,
                'is_active' => true,
            ]
        );
        $orientador->assignRole('orientador');
    }
}