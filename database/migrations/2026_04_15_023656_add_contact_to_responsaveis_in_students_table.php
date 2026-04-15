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
            $table->string('responsavel_email',    255)->nullable()->after('responsavel_nome');
            $table->string('responsavel_telefone',  30)->nullable()->after('responsavel_email');
            $table->string('responsavel_2_email',  255)->nullable()->after('responsavel_2_nome');
            $table->string('responsavel_2_telefone', 30)->nullable()->after('responsavel_2_email');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'responsavel_email', 'responsavel_telefone',
                'responsavel_2_email', 'responsavel_2_telefone',
            ]);
        });
    }
};
