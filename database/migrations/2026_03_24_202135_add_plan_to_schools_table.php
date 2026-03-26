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
    Schema::table('schools', function (Blueprint $table) {
        $table->string('plan')->default('pro')->after('is_active'); // pro | enterprise
        $table->string('plan_status')->default('active')->after('plan'); // active | suspended | cancelled
        $table->date('plan_expires_at')->nullable()->after('plan_status');
        $table->integer('max_students')->default(100)->after('plan_expires_at');
        $table->text('notes')->nullable()->after('max_students'); // observações internas
    });
}

public function down(): void
{
    Schema::table('schools', function (Blueprint $table) {
        $table->dropColumn(['plan', 'plan_status', 'plan_expires_at', 'max_students', 'notes']);
    });
}
};
