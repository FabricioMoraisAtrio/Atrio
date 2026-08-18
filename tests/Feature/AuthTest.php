<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\CriaEscolaEUsuarios;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase, CriaEscolaEUsuarios;

    protected function setUp(): void
    {
        parent::setUp();
        $this->criarEscolaEUsuarios();
    }

    public function test_login_com_credenciais_corretas(): void
    {
        $response = $this->post(route('login.store'), [
            'email'    => 'secretaria@teste.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('secretaria.dashboard'));
    }

    public function test_login_com_credenciais_erradas(): void
    {
        $response = $this->post(route('login.store'), [
            'email'    => 'secretaria@teste.com',
            'password' => 'errada',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_professor_acessa_portal_unificado(): void
    {
        // Portal unificado: o professor usa as mesmas rotas, entrando escopado.
        $this->loginComo($this->professor);

        $this->get(route('secretaria.dashboard'))->assertOk();
        $this->get(route('secretaria.painel'))->assertOk();
        $this->get(route('secretaria.turmas.index'))->assertOk();
    }

    public function test_pai_nao_acessa_portal(): void
    {
        $this->loginComo($this->pai);

        $response = $this->get(route('secretaria.dashboard'));

        $response->assertStatus(403);
    }

    public function test_usuario_nao_autenticado_e_redirecionado(): void
    {
        $response = $this->get(route('secretaria.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_logout(): void
    {
        $this->loginComo($this->secretaria);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}