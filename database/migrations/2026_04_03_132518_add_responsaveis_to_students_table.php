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
        Schema::table('students', function (Blueprint $table) {
            $table->string('responsavel_nome')->nullable()->after('birth_date');
            $table->string('responsavel_2_nome')->nullable()->after('responsavel_nome');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['responsavel_nome', 'responsavel_2_nome']);
        });
    }
};
