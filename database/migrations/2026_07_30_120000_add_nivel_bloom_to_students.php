<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Nível do estudante na Taxonomia de Bloom (revisada). Preenchido no Estudo de Caso / cadastro. */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('nivel_bloom', 20)->nullable()->after('condition');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('nivel_bloom');
        });
    }
};
