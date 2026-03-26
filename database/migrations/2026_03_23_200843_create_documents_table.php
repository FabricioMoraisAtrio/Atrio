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
    Schema::create('documents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('school_id')->constrained()->cascadeOnDelete();
        $table->foreignId('student_id')->constrained()->cascadeOnDelete();
        $table->foreignId('author_id')->constrained('users');
        $table->string('type');    // pei | paee | estudo_caso
        $table->string('status')->default('draft'); // draft | published
        $table->json('content');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
