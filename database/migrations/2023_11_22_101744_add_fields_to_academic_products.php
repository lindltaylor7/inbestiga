<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_products', function (Blueprint $table) {
            $table->unsignedBigInteger('academic_process_id')->nullable();
            $table->foreign('academic_process_id')->references('id')->on('academic_processes')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('academic_products', function (Blueprint $table) {
            $table->dropForeign(['academic_process_id']);
            $table->dropColumn('academic_process_id');   
        });
    }
};
