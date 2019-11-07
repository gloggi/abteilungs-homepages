<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPageRelationColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->unsignedInteger('group_form_page_id')->nullable();
            $table->foreign('group_form_page_id')->references('id')->on('pages');
            $table->unsignedInteger('group_agenda_page_id')->nullable();
            $table->foreign('group_agenda_page_id')->references('id')->on('pages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('group_form_page_id');
            $table->dropColumn('group_agenda_page_id');
        });
    }
}
