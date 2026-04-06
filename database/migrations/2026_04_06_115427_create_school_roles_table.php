<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('name');           // Label exibido: "Coordenador Pedagógico"
            $table->string('slug');           // Slug interno: "coordenador_pedagogico"
            $table->string('spatie_role');    // Nome do role Spatie: "s8_coordenador_pedagogico"
            $table->string('color', 20)->default('#6B7280');
            $table->json('permissions')->nullable();
            $table->boolean('is_system')->default(false); // true = admin/coordenador/orientador/professor
            $table->timestamps();
            $table->unique(['school_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_roles');
    }
};
