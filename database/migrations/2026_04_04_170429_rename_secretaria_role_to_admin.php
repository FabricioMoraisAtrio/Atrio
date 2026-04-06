<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Renomeia o role 'secretaria' para 'admin' em dados existentes
        DB::table('roles')->where('name', 'secretaria')->update(['name' => 'admin']);
    }

    public function down(): void
    {
        DB::table('roles')->where('name', 'admin')->update(['name' => 'secretaria']);
    }
};
