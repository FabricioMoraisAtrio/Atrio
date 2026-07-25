<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * "Reuniões / Atas" e "Linha do Tempo" viraram módulos próprios. Escolas com
     * lista de módulos explícita (não-nula) precisam recebê-los para não perderem
     * as rotinas. Escolas com modules = null (todos habilitados) não são tocadas.
     */
    public function up(): void
    {
        foreach (DB::table('schools')->whereNotNull('modules')->get() as $s) {
            $mods = json_decode($s->modules, true);
            if (! is_array($mods)) {
                continue;
            }

            $changed = false;
            foreach (['reunioes', 'linha_do_tempo'] as $key) {
                if (! in_array($key, $mods, true)) {
                    $mods[]  = $key;
                    $changed = true;
                }
            }

            if ($changed) {
                DB::table('schools')->where('id', $s->id)->update(['modules' => json_encode($mods)]);
            }
        }
    }

    public function down(): void
    {
        // Sem reversão: manter os módulos habilitados é o estado correto.
    }
};
