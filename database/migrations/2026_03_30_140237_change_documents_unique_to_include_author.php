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
        Schema::table('documents', function (Blueprint $table) {
            $table->dropUnique(['student_id', 'type', 'year']);
            $table->unique(['student_id', 'type', 'year', 'author_id'], 'documents_student_type_year_author_unique');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropUnique('documents_student_type_year_author_unique');
            $table->unique(['student_id', 'type', 'year']);
        });
    }
};
