<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLog extends Model
{
    public const UPDATED_AT = null; // só created_at

    protected $fillable = ['admin_user_id', 'school_id', 'action', 'description', 'created_at'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /** Registra uma ação do painel superadmin. */
    public static function record(string $action, string $description, ?int $schoolId = null): void
    {
        static::create([
            'admin_user_id' => auth('admin')->id(),
            'school_id'     => $schoolId,
            'action'        => $action,
            'description'   => $description,
            'created_at'    => now(),
        ]);
    }

    public static function actionLabels(): array
    {
        return [
            'escola_criada'      => 'Escola criada',
            'escola_editada'     => 'Escola editada',
            'escola_removida'    => 'Escola removida',
            'fatura_criada'      => 'Fatura criada',
            'faturas_geradas'    => 'Faturas geradas',
            'fatura_paga'        => 'Fatura paga',
            'fatura_cancelada'   => 'Fatura cancelada',
            'fatura_excluida'    => 'Fatura excluída',
            'comunicado_criado'  => 'Comunicado criado',
            'comunicado_editado' => 'Comunicado editado',
            'comunicado_removido'=> 'Comunicado removido',
            'admin_criado'       => 'Administrador criado',
            'admin_editado'      => 'Administrador editado',
            'admin_removido'     => 'Administrador removido',
            'alunos_importados'  => 'Estudantes importados',
            '2fa_ativado'        => '2FA ativado',
            '2fa_desativado'     => '2FA desativado',
        ];
    }
}
