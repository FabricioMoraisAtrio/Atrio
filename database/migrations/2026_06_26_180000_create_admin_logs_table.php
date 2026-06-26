<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admin_logs')) {
            return;
        }

        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_user_id')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->string('action', 50);
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('action');
            $table->index('school_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
