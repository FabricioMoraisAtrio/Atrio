<?php

namespace Tests\Feature;

use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CriaEscolaEUsuarios;

class ConfigModulosTest extends TestCase
{
    use RefreshDatabase, CriaEscolaEUsuarios;

    protected function setUp(): void
    {
        parent::setUp();
        $this->criarEscolaEUsuarios();
        $this->loginComo($this->secretaria); // role admin
    }

    public function test_admin_salva_modulos_e_preserva_essenciais(): void
    {
        $this->put(route('secretaria.config.modulos.update'), [
            'modules' => ['alunos', 'documentos'],
        ])->assertRedirect();

        $escola = School::withoutGlobalScopes()->find($this->escola->id);

        // Selecionados ficam ativos.
        $this->assertContains('alunos', $escola->modules);
        $this->assertContains('documentos', $escola->modules);
        // Essenciais sempre presentes, mesmo sem terem sido enviados.
        $this->assertContains('configuracoes', $escola->modules);
        $this->assertContains('painel', $escola->modules);
        // Não enviados ficam de fora.
        $this->assertNotContains('turmas', $escola->modules);

        $this->assertTrue($escola->hasModule('alunos'));
        $this->assertFalse($escola->hasModule('turmas'));
    }
}
