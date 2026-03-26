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
        $table->string('year', 4)->default(date('Y'))->after('type');
        $table->unique(['student_id', 'type', 'year']);
    });
}

public function down(): void
{
    Schema::table('documents', function (Blueprint $table) {
        $table->dropUnique(['student_id', 'type', 'year']);
        $table->dropColumn('year');
    });
}
};
