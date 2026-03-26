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
    Schema::table('users', function (Blueprint $table) {
        $table->string('avatar')->nullable()->after('name');
        $table->boolean('notify_document_pending')->default(true)->after('is_active');
        $table->boolean('notify_plan_expiring')->default(true)->after('notify_document_pending');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['avatar', 'notify_document_pending', 'notify_plan_expiring']);
    });
}
};
