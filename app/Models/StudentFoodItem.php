<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentFoodItem extends Model
{
    protected $fillable = ['student_id', 'school_id', 'category', 'name', 'status', 'notes'];

    public const CATEGORIES = [
        'frutas'    => 'Frutas',
        'vegetais'  => 'Vegetais e Legumes',
        'proteinas' => 'Proteínas',
        'graos'     => 'Grãos e Carboidratos',
        'laticinios'=> 'Laticínios',
        'bebidas'   => 'Bebidas',
        'outros'    => 'Outros',
    ];

    public const STATUSES = [
        'aceita'  => ['label' => 'Aceita',  'bg' => '#ECFDF5', 'color' => '#065F46'],
        'tolera'  => ['label' => 'Tolera',  'bg' => '#FEF3C7', 'color' => '#92400E'],
        'recusa'  => ['label' => 'Recusa',  'bg' => '#FEF2F2', 'color' => '#991B1B'],
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
