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
    Schema::create('laudos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('school_id')->constrained()->cascadeOnDelete();
        $table->foreignId('student_id')->constrained()->cascadeOnDelete();
        $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
        $table->string('tipo'); // medico, psicologico, fonoaudiologico, neuropediatrico, outro
        $table->string('descricao')->nullable();
        $table->string('arquivo'); // path do PDF
        $table->date('data_laudo');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('laudos');
}
};
