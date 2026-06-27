<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, Notifiable;
protected $fillable = [
    'name', 'email', 'password', 'school_id', 'is_active',
    'avatar', 'notify_document_pending', 'notify_plan_expiring', 'pdf_preview', 'theme',
];

    protected $hidden = ['password', 'remember_token'];

 protected function casts(): array
{
    return [
        'password'                 => 'hashed',
        'is_active'                => 'boolean',
        'email_verified_at'        => 'datetime',
        'notify_document_pending'  => 'boolean',
        'notify_plan_expiring'     => 'boolean',
        'pdf_preview'              => 'boolean',
    ];
}

    // SEM booted() aqui — User nunca recebe SchoolScope

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function schoolClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'school_class_user')
                    ->withPivot('subject')
                    ->withTimestamps();
    }
    public function children(): BelongsToMany
{
    return $this->belongsToMany(Student::class, 'student_user')
                ->withTimestamps();
}

    /** E-mail de redefinição de senha com a identidade visual do Átrio (em PT). */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}