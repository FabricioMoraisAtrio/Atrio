<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'school_id', 'reference', 'amount', 'due_date',
        'status', 'paid_at', 'method', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount'   => 'decimal:2',
            'due_date' => 'date',
            'paid_at'  => 'date',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /** Está em aberto e já passou da data de vencimento. */
    public function isOverdue(): bool
    {
        return $this->status === 'aberto'
            && $this->due_date
            && $this->due_date->isPast();
    }

    /** Status efetivo (considera vencimento de faturas em aberto). */
    public function effectiveStatus(): string
    {
        return $this->isOverdue() ? 'vencido' : $this->status;
    }

    public static function statusLabels(): array
    {
        return [
            'aberto'    => 'Em aberto',
            'pago'      => 'Pago',
            'vencido'   => 'Vencido',
            'cancelado' => 'Cancelado',
        ];
    }

    public function scopePago($q)
    {
        return $q->where('status', 'pago');
    }

    public function scopeEmAberto($q)
    {
        return $q->where('status', 'aberto');
    }
}
