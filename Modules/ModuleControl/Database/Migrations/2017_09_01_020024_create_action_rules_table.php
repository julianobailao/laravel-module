<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_rules', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->uuid('action_id');
            $table->string('module_name');
            $table->string('route_uri');
            $table->string('route_method');

            $table->timestamps();

            $table->foreign('action_id')
                ->references('id')->on('actions')->onDelete('cascade');

            $table->unique([
                'action_id', 'module_name', 'route_uri', 'route_method'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_rules');
    }
}
