<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('subject_inventory_items')
            ->where('categoria', 'funcional')
            ->update(['categoria' => 'global']);
    }

    public function down(): void
    {
        DB::table('subject_inventory_items')
            ->where('categoria', 'global')
            ->update(['categoria' => 'funcional']);
    }
};
