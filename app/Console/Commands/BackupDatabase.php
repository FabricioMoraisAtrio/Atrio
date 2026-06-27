<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'atrio:backup {--keep=14 : Quantos backups manter}';

    protected $description = 'Gera um backup do banco (.sql.gz) em storage/app/backups, com retenção.';

    public function handle(): int
    {
        $disk = Storage::disk('local');
        $disk->makeDirectory('backups');

        $conn = config('database.default');
        $stamp = now()->format('Y-m-d_His');

        try {
            if ($conn === 'sqlite') {
                $src = config('database.connections.sqlite.database');
                $dest = "backups/atrio_{$stamp}.sqlite.gz";
                $disk->put($dest, gzencode(file_get_contents($src), 6));
            } else {
                $sql = $this->dumpMysql();
                $dest = "backups/atrio_{$stamp}.sql.gz";
                $disk->put($dest, gzencode($sql, 6));
            }
        } catch (\Throwable $e) {
            $this->error('Falha no backup: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->prune((int) $this->option('keep'));
        $this->info('Backup gerado: ' . $dest . ' (' . $this->human($disk->size($dest)) . ')');

        return self::SUCCESS;
    }

    /** Dump puro-PHP do MySQL (sem depender de mysqldump). */
    private function dumpMysql(): string
    {
        $pdo = DB::getPdo();
        $out = "-- Átrio backup " . now()->toDateTimeString() . "\nSET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach (DB::select('SHOW TABLES') as $row) {
            $table = array_values((array) $row)[0];

            $create = (array) DB::select("SHOW CREATE TABLE `{$table}`")[0];
            $out .= "DROP TABLE IF EXISTS `{$table}`;\n" . end($create) . ";\n\n";

            $rows = DB::table($table)->get();
            if ($rows->isEmpty()) {
                continue;
            }
            $cols = array_keys((array) $rows->first());
            $colList = '`' . implode('`,`', $cols) . '`';

            foreach ($rows->chunk(200) as $chunk) {
                $values = [];
                foreach ($chunk as $r) {
                    $vals = array_map(function ($v) use ($pdo) {
                        return $v === null ? 'NULL' : $pdo->quote((string) $v);
                    }, array_values((array) $r));
                    $values[] = '(' . implode(',', $vals) . ')';
                }
                $out .= "INSERT INTO `{$table}` ({$colList}) VALUES\n" . implode(",\n", $values) . ";\n";
            }
            $out .= "\n";
        }

        return $out . "SET FOREIGN_KEY_CHECKS=1;\n";
    }

    /** Mantém apenas os N backups mais recentes. */
    private function prune(int $keep): void
    {
        $disk = Storage::disk('local');
        $files = collect($disk->files('backups'))
            ->filter(fn ($f) => str_contains($f, 'atrio_'))
            ->sortDesc()
            ->values();

        foreach ($files->slice($keep) as $old) {
            $disk->delete($old);
        }
    }

    private function human(int $bytes): string
    {
        foreach (['B', 'KB', 'MB', 'GB'] as $u) {
            if ($bytes < 1024) {
                return round($bytes, 1) . ' ' . $u;
            }
            $bytes /= 1024;
        }
        return round($bytes, 1) . ' TB';
    }
}
