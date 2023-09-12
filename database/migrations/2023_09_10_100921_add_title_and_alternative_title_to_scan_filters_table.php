<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitleAndAlternativeTitleToScanFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scan_filters', function (Blueprint $table) {
            $table->string('title_filter')->nullable();
            $table->string('alternative_title_filter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scan_filters', function (Blueprint $table) {
            $table->dropColumn('title_filter');
            $table->dropColumn('alternative_title_filter');
        });
    }
}
