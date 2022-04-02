<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScanFilterIdColumnInSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sources', function (Blueprint $table) {
            //
            $table->foreignId('scan_filter_id')
                ->after('url')
                ->nullable()
                ->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->dropForeign(['scan_filter_id']);
            $table->dropColumn('scan_filter_id');
        });
    }
}
