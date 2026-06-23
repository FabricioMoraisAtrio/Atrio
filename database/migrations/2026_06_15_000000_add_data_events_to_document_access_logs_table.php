<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adiciona colunas de snapshot ao log de acesso (LGPD) e remove o cascade
     * de document_id/user_id para preservar o histórico após exclusões.
     *
     * Reescrita para ser idempotente e compatível com MySQL e SQLite. A versão
     * anterior usava rename + recriação de tabela, que falhava no MySQL
     * (colisão de nomes de foreign key) e podia deixar a tabela
     * "document_access_logs_old" como resíduo de tentativas com erro.
     */
    public function up(): void
    {
        $hasNew = Schema::hasTable('document_access_logs');
        $hasOld = Schema::hasTable('document_access_logs_old');

        // Recupera estado de tentativa anterior em que o rename ocorreu mas a
        // recriação falhou (sobrou apenas a tabela _old com os dados originais).
        if (! $hasNew && $hasOld) {
            Schema::rename('document_access_logs_old', 'document_access_logs');
            $hasNew = true;
            $hasOld = false;
        }

        // Ambiente novo: cria já no schema final (sem FKs, para evitar colisões).
        if (! $hasNew) {
            Schema::create('document_access_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->nullable();
                $table->unsignedBigInteger('document_id')->nullable();
                $table->unsignedBigInteger('student_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('action'); // viewed | edited | exported | created | deleted
                $table->string('document_type')->nullable();
                $table->unsignedSmallInteger('document_year')->nullable();
                $table->string('student_name')->nullable();
                $table->string('ip')->nullable();
                $table->timestamp('accessed_at');
            });

            Schema::dropIfExists('document_access_logs_old');

            return;
        }

        // Adiciona as colunas novas, se faltarem (idempotente, cross-DB).
        Schema::table('document_access_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('document_access_logs', 'school_id'))     $table->unsignedBigInteger('school_id')->nullable()->after('id');
            if (! Schema::hasColumn('document_access_logs', 'student_id'))    $table->unsignedBigInteger('student_id')->nullable();
            if (! Schema::hasColumn('document_access_logs', 'document_type')) $table->string('document_type')->nullable();
            if (! Schema::hasColumn('document_access_logs', 'document_year')) $table->unsignedSmallInteger('document_year')->nullable();
            if (! Schema::hasColumn('document_access_logs', 'student_name'))  $table->string('student_name')->nullable();
        });

        // Remove o cascade de document_id/user_id para preservar logs após a
        // exclusão de documentos/usuários (best-effort: pode não existir).
        foreach (['document_id', 'user_id'] as $col) {
            try {
                Schema::table('document_access_logs', function (Blueprint $table) use ($col) {
                    $table->dropForeign([$col]);
                });
            } catch (\Throwable $e) {
                // Constraint inexistente, nome diferente ou driver sem suporte — ignora.
            }
        }

        // Remove resíduo de tentativa anterior.
        Schema::dropIfExists('document_access_logs_old');

        // Backfill das colunas novas a partir dos documentos (apenas onde vazio).
        DB::table('document_access_logs')
            ->whereNull('school_id')
            ->whereNotNull('document_id')
            ->orderBy('id')
            ->chunkById(500, function ($logs) {
                foreach ($logs as $log) {
                    $doc = DB::table('documents')->where('id', $log->document_id)->first();
                    if (! $doc) {
                        continue;
                    }
                    $studentName = $doc->student_id
                        ? DB::table('students')->where('id', $doc->student_id)->value('name')
                        : null;

                    DB::table('document_access_logs')->where('id', $log->id)->update([
                        'school_id'     => $doc->school_id,
                        'student_id'    => $doc->student_id,
                        'document_type' => $doc->type,
                        'document_year' => $doc->year,
                        'student_name'  => $studentName,
                    ]);
                }
            });
    }

    public function down(): void
    {
        foreach (['school_id', 'student_id', 'document_type', 'document_year', 'student_name'] as $col) {
            if (Schema::hasColumn('document_access_logs', $col)) {
                Schema::table('document_access_logs', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }
    }
};
