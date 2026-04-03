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
        Schema::table('students', function (Blueprint $table) {
            $table->boolean('cid_ahsd')->default(false)->after('cid_outros');
            $table->boolean('cid_dalt')->default(false)->after('cid_ahsd');
            $table->boolean('cid_dfm')->default(false)->after('cid_dalt');
            $table->boolean('cid_disl')->default(false)->after('cid_dfm');
            $table->boolean('cid_epile')->default(false)->after('cid_disl');
            $table->boolean('cid_mc')->default(false)->after('cid_epile');
            $table->boolean('cid_mult')->default(false)->after('cid_mc');
            $table->boolean('cid_si')->default(false)->after('cid_mult');
            $table->boolean('cid_tag')->default(false)->after('cid_si');
            $table->boolean('cid_tda')->default(false)->after('cid_tag');
            $table->boolean('cid_tdc')->default(false)->after('cid_tda');
            $table->boolean('cid_tdl')->default(false)->after('cid_tdc');
            $table->boolean('cid_tod')->default(false)->after('cid_tdl');
            $table->boolean('cid_tpac')->default(false)->after('cid_tod');
            $table->boolean('cid_tps')->default(false)->after('cid_tpac');
            $table->boolean('cid_th')->default(false)->after('cid_tps');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'cid_ahsd','cid_dalt','cid_dfm','cid_disl','cid_epile',
                'cid_mc','cid_mult','cid_si','cid_tag','cid_tda',
                'cid_tdc','cid_tdl','cid_tod','cid_tpac','cid_tps','cid_th',
            ]);
        });
    }
};
