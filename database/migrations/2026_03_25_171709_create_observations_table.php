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
    Schema::create('observations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('school_id')->constrained()->cascadeOnDelete();
        $table->foreignId('student_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->text('content');
        $table->string('urgency')->default('normal'); // normal | atencao | critico
        $table->string('category');                   // comportamento | aprendizado | saude
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('observations');
}
};
