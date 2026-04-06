<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('subjects')) {
            return;
        }

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('name');                                    // "Língua Portuguesa"
            $table->string('slug');                                    // "portugues"
            $table->string('label_responsavel')->default('Prof.');     // label no inventário
            $table->enum('tipo', ['disciplina', 'regente'])->default('disciplina');
            $table->unsignedSmallInteger('ordem')->default(0);
            $table->timestamps();

            $table->unique(['school_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
