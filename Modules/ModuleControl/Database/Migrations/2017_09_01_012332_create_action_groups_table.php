<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->uuid('id');
            $table->string('title');
            $table->text('description')->nullable();

            $table->text('list_title')->nullable();
            $table->text('list_subtitle')->nullable();
            $table->text('list_description')->nullable();

            $table->text('form_title')->nullable();
            $table->text('form_subtitle')->nullable();
            $table->text('form_description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_groups');
    }
}
