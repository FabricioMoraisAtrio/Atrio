<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('document_access_logs', 'document_access_logs_old');

        Schema::create('document_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // viewed | edited | exported | created | deleted
            $table->string('document_type')->nullable();
            $table->unsignedSmallInteger('document_year')->nullable();
            $table->string('student_name')->nullable();
            $table->string('ip')->nullable();
            $table->timestamp('accessed_at');
        });

        DB::statement('
            INSERT INTO document_access_logs
                (id, school_id, document_id, student_id, user_id, action, document_type, document_year, student_name, ip, accessed_at)
            SELECT o.id, d.school_id, o.document_id, d.student_id, o.user_id, o.action, d.type, d.year, s.name, o.ip, o.accessed_at
            FROM document_access_logs_old o
            JOIN documents d ON d.id = o.document_id
            LEFT JOIN students s ON s.id = d.student_id
        ');

        Schema::dropIfExists('document_access_logs_old');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('document_access_logs_old', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action');
            $table->string('ip')->nullable();
            $table->timestamp('accessed_at');
        });

        DB::statement('
            INSERT INTO document_access_logs_old (id, document_id, user_id, action, ip, accessed_at)
            SELECT id, document_id, user_id, action, ip, accessed_at
            FROM document_access_logs
            WHERE document_id IS NOT NULL AND user_id IS NOT NULL
        ');

        Schema::dropIfExists('document_access_logs');

        Schema::rename('document_access_logs_old', 'document_access_logs');
    }
};
