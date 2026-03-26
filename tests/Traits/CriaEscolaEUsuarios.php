<?php

namespace Tests\Traits;

use App\Models\School;
use App\Models\User;
use Spatie\Permission\Models\Role;

trait CriaEscolaEUsuarios
{
    protected School $escola;
    protected User $secretaria;
    protected User $professor;
    protected User $pai;

    protected function criarEscolaEUsuarios(): void
    {
        // Cria roles se não existirem
        foreach (['secretaria', 'professor', 'pai'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $this->escola = School::create([
            'name'         => 'Escola Teste',
            'slug'         => 'escola-teste',
            'is_active'    => true,
            'plan'         => 'pro',
            'plan_status'  => 'active',
            'max_students' => 100,
        ]);

        $this->secretaria = User::create([
            'name'      => 'Secretaria Teste',
            'email'     => 'secretaria@teste.com',
            'password'  => bcrypt('password'),
            'school_id' => $this->escola->id,
            'is_active' => true,
        ]);
        $this->secretaria->assignRole('secretaria');

        $this->professor = User::create([
            'name'      => 'Professor Teste',
            'email'     => 'professor@teste.com',
            'password'  => bcrypt('password'),
            'school_id' => $this->escola->id,
            'is_active' => true,
        ]);
        $this->professor->assignRole('professor');

        $this->pai = User::create([
            'name'      => 'Pai Teste',
            'email'     => 'pai@teste.com',
            'password'  => bcrypt('password'),
            'school_id' => $this->escola->id,
            'is_active' => true,
        ]);
        $this->pai->assignRole('pai');
    }

    protected function loginComo(User $user): void
    {
        $this->actingAs($user);
        session(['school_id' => $this->escola->id]);
    }
}