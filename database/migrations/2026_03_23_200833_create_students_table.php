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
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->foreignId('school_id')->constrained()->cascadeOnDelete();
        $table->string('name');
        $table->string('registration_number');
        $table->date('birth_date');
        $table->boolean('is_atypical')->default(false);
        $table->string('condition')->nullable();
        $table->boolean('has_case_study')->default(false);
        $table->timestamps();

        $table->unique(['school_id', 'registration_number']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
