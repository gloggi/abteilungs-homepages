<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('banner')->nullable();
            $table->integer('sort_order');
            $table->integer('age_min');
            $table->integer('age_max');
            $table->string('color');
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('annual_plan')->nullable();
            $table->timestamps();
        });
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('banner')->nullable();
            $table->integer('sort_order');
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('id')->on('users');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->char('gender', 1)->nullable();
            $table->string('logo')->nullable();
            $table->string('color')->nullable();
            $table->string('geographic_area')->nullable();
            $table->string('description')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_name');
            $table->string('annual_plan')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('groups');
            $table->integer('lft');
            $table->integer('rgt');
            $table->integer('depth');
            $table->timestamps();
        });
        Schema::create('leaders', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['group_id', 'user_id']);
        });
        Schema::create('group_transitions', function (Blueprint $table) {
            $table->unsignedBigInteger('from_group_id');
            $table->foreign('from_group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->unsignedBigInteger('to_group_id');
            $table->foreign('to_group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->primary(['from_group_id', 'to_group_id']);
        });
        Schema::create('highlight_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->string('image');
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('coordinates');
            $table->timestamps();
        });
        Schema::create('special_event_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('sort_order');
            $table->string('plural_name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->dateTime('start_time');
            $table->unsignedBigInteger('start_location_id');
            $table->foreign('start_location_id')->references('id')->on('locations');
            $table->dateTime('end_time');
            $table->unsignedBigInteger('end_location_id')->nullable();
            $table->foreign('end_location_id')->references('id')->on('locations');
            $table->string('to_bring')->nullable();
            $table->timestamps();
        });
        Schema::create('event_owners', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['event_id', 'user_id']);
        });
        Schema::create('event_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->primary(['event_id', 'group_id']);
        });
        Schema::create('downloads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('file');
            $table->timestamps();
        });
        Schema::create('event_special_event_types', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('special_event_type_id');
            $table->foreign('special_event_type_id')->references('id')->on('special_event_types')->onDelete('cascade');
            $table->primary(['event_id', 'special_event_type_id']);
        });
        Schema::create('staff_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('sort_order');
            $table->string('person_name')->nullable();
            $table->string('email');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_contacts');
        Schema::dropIfExists('event_special_event_types');
        Schema::dropIfExists('downloads');
        Schema::dropIfExists('event_groups');
        Schema::dropIfExists('event_owners');
        Schema::dropIfExists('events');
        Schema::dropIfExists('special_event_types');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('highlight_images');
        Schema::dropIfExists('group_transitions');
        Schema::dropIfExists('leaders');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('sections');
    }
}
