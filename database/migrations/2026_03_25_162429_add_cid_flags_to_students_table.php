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
        $table->boolean('cid_autismo')->default(false)->after('condition');
        $table->boolean('cid_tdah')->default(false)->after('cid_autismo');
        $table->boolean('cid_down')->default(false)->after('cid_tdah');
        $table->boolean('cid_deficiencia_intelectual')->default(false)->after('cid_down');
        $table->boolean('cid_deficiencia_visual')->default(false)->after('cid_deficiencia_intelectual');
        $table->boolean('cid_deficiencia_auditiva')->default(false)->after('cid_deficiencia_visual');
        $table->boolean('cid_outros')->default(false)->after('cid_deficiencia_auditiva');
    });
}

public function down(): void
{
    Schema::table('students', function (Blueprint $table) {
        $table->dropColumn([
            'cid_autismo', 'cid_tdah', 'cid_down',
            'cid_deficiencia_intelectual', 'cid_deficiencia_visual',
            'cid_deficiencia_auditiva', 'cid_outros',
        ]);
    });
}
};
