<?php

namespace App\Support;

use App\Models\SchoolSetting;

/**
 * Catálogo de campos dos documentos cuja obrigatoriedade é configurável por escola
 * (gerido pelo superadmin). A lista de obrigatórios fica em SchoolSetting
 * (chave "campos_obrigatorios", JSON por tipo de documento). Sem config → PADRAO.
 */
class CamposDocumento
{
    /** Campos alternáveis como obrigatórios, por tipo de documento. tipo: texto | lista */
    public const CATALOGO = [
        'estudo_caso' => [
            'historico_escolar'      => ['label' => 'Histórico escolar', 'tipo' => 'texto'],
            'contexto_familiar'      => ['label' => 'Contexto familiar', 'tipo' => 'texto'],
            'frequencia_assiduidade' => ['label' => 'Frequência e assiduidade', 'tipo' => 'texto'],
            'barreiras'              => ['label' => 'Barreiras identificadas', 'tipo' => 'lista'],
            'potencialidades'        => ['label' => 'Potencialidades', 'tipo' => 'lista'],
            'encaminhamentos'        => ['label' => 'Encaminhamentos', 'tipo' => 'lista'],
        ],
        'paee' => [
            'recursos_estrategias'    => ['label' => 'Recursos e estratégias', 'tipo' => 'lista'],
            'organizacao_atendimento' => ['label' => 'Organização do atendimento', 'tipo' => 'lista'],
            'avaliacao_monitoramento' => ['label' => 'Avaliação e monitoramento', 'tipo' => 'lista'],
        ],
    ];

    /** Padrão quando a escola ainda não configurou (mantém o comportamento vigente). */
    public const PADRAO = [
        'estudo_caso' => ['historico_escolar'],
        'paee'        => [],
    ];

    private const SETTING_KEY = 'campos_obrigatorios';

    /** Tipos de documento cobertos por este catálogo. */
    public static function tipos(): array
    {
        return array_keys(self::CATALOGO);
    }

    /** Config completa de obrigatórios da escola (por tipo => [chaves]). */
    public static function configDaEscola(int $schoolId): array
    {
        $raw = SchoolSetting::getValue($schoolId, self::SETTING_KEY, '');
        $cfg = $raw !== '' ? (json_decode($raw, true) ?: []) : [];

        $out = [];
        foreach (self::CATALOGO as $type => $campos) {
            if (array_key_exists($type, $cfg)) {
                $out[$type] = array_values(array_intersect(array_keys($campos), (array) $cfg[$type]));
            } else {
                $out[$type] = self::PADRAO[$type] ?? [];
            }
        }

        return $out;
    }

    /** Chaves obrigatórias para escola + tipo. */
    public static function obrigatorios(int $schoolId, string $type): array
    {
        return self::configDaEscola($schoolId)[$type] ?? [];
    }

    /** Regras + mensagens de validação para escola + tipo. */
    public static function regras(?int $schoolId, string $type): array
    {
        $rules = [];
        $messages = [];

        if ($schoolId) {
            foreach (self::obrigatorios($schoolId, $type) as $key) {
                $campo = self::CATALOGO[$type][$key] ?? null;
                if (! $campo) {
                    continue;
                }
                $rules[$key] = $campo['tipo'] === 'lista' ? 'required|array|min:1' : 'required|string';
                $messages["{$key}.required"] = 'Preencha: ' . $campo['label'] . '.';
                $messages["{$key}.min"]      = 'Selecione ao menos um item em ' . $campo['label'] . '.';
            }
        }

        return ['rules' => $rules, 'messages' => $messages];
    }

    /** true se o campo é obrigatório para a escola (para o marcador * no formulário). */
    public static function ehObrigatorio(?int $schoolId, string $type, string $key): bool
    {
        return $schoolId ? in_array($key, self::obrigatorios($schoolId, $type), true) : false;
    }

    /** Persiste a config (recebe [type => [chaves marcadas]]). */
    public static function salvar(int $schoolId, array $porTipo): void
    {
        $limpo = [];
        foreach (self::CATALOGO as $type => $campos) {
            $limpo[$type] = array_values(array_intersect(array_keys($campos), (array) ($porTipo[$type] ?? [])));
        }
        SchoolSetting::setValue($schoolId, self::SETTING_KEY, json_encode($limpo));
    }
}
