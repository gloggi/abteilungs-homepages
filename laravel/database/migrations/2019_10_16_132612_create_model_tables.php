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
            $table->string('name')->nullable(false);
            $table->string('banner');
            $table->integer('sort_order')->nullable(false);
            $table->integer('age_min')->nullable(false);
            $table->integer('age_max')->nullable(false);
            $table->string('color')->nullable(false);
            $table->string('description');
            $table->string('logo');
            $table->string('annual_plan');
            $table->timestamps();
        });
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(false);
            $table->string('banner');
            $table->integer('sort_order')->nullable(false);
            $table->unsignedBigInteger('contact_id')->nullable(false);
            $table->foreign('contact_id')->references('id')->on('users');
            $table->unsignedBigInteger('section_id')->nullable(false);
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->char('gender', 1);
            $table->string('logo');
            $table->string('color');
            $table->string('geographic_area');
            $table->string('description');
            $table->string('contact_email');
            $table->string('contact_name')->nullable(false);
            $table->string('annual_plan');
            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')->references('id')->on('groups');
            $table->integer('lft')->nullable(false);
            $table->integer('rgt')->nullable(false);
            $table->integer('depth')->nullable(false);
            $table->timestamps();
        });
        Schema::create('leaders', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable(false);
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['group_id', 'user_id']);
        });
        Schema::create('group_transitions', function (Blueprint $table) {
            $table->unsignedBigInteger('from_group_id')->nullable(false);
            $table->foreign('from_group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->unsignedBigInteger('to_group_id')->nullable(false);
            $table->foreign('to_group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->primary(['from_group_id', 'to_group_id']);
        });
        Schema::create('highlight_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id')->nullable(false);
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->string('image')->nullable(false);
            $table->string('description');
            $table->timestamps();
        });
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(false);
            $table->string('coordinates')->nullable(false);
            $table->timestamps();
        });
        Schema::create('special_event_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(false);
            $table->integer('sort_order')->nullable(false);
            $table->string('plural_name')->nullable(false);
            $table->string('description');
            $table->timestamps();
        });
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(false);
            $table->string('description');
            $table->dateTime('start_time')->nullable(false);
            $table->unsignedBigInteger('start_location_id')->nullable(false);
            $table->foreign('start_location_id')->references('id')->on('locations');
            $table->dateTime('end_time')->nullable(false);
            $table->unsignedBigInteger('end_location_id');
            $table->foreign('end_location_id')->references('id')->on('locations');
            $table->string('to_bring');
            $table->timestamps();
        });
        Schema::create('event_owners', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable(false);
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['event_id', 'user_id']);
        });
        Schema::create('event_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable(false);
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('group_id')->nullable(false);
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->primary(['event_id', 'group_id']);
        });
        Schema::create('downloads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_id')->nullable(false);
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->string('name');
            $table->string('file')->nullable(false);
            $table->timestamps();
        });
        Schema::create('event_special_event_types', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable(false);
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('special_event_type_id')->nullable(false);
            $table->foreign('special_event_type_id')->references('id')->on('special_event_types')->onDelete('cascade');
            $table->primary(['event_id', 'special_event_type_id']);
        });
        Schema::create('staff_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(false);
            $table->integer('sort_order')->nullable(false);
            $table->string('person_name');
            $table->string('email')->nullable(false);
            $table->string('image')->nullable(false);
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
