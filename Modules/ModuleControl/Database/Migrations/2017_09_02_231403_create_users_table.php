<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->uuid('id');
            $table->uuid('user_group_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->foreign('user_group_id')
                ->references('id')->on('user_groups')->onDelete('cascade');
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        if (! app()->environment('production')) {
            $userGroup = \Modules\ModuleControl\Entities\UserGroup::create(['title' => 'desenvolvimento']);
            $userGroup->users()->create([
                'name' => 'Desenvolvimento UDS',
                'email' => 'desenvolvimento@uds.com.br',
                'password' => bcrypt('teste@00!!'),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
    }
}
