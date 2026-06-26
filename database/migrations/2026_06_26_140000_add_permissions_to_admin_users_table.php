<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_users', function (Blueprint $table) {
            if (! Schema::hasColumn('admin_users', 'permissions')) {
                // null = acesso total (owner); array = rotinas permitidas
                $table->json('permissions')->nullable()->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admin_users', function (Blueprint $table) {
            if (Schema::hasColumn('admin_users', 'permissions')) {
                $table->dropColumn('permissions');
            }
        });
    }
};
