<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageFilterToScanFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scan_filters', function (Blueprint $table) {
            $table->string('image_filter')->nullable(); // Define the new column
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
            $table->dropColumn('image_filter'); // Define how to rollback the migration if needed
        });
    }
}
