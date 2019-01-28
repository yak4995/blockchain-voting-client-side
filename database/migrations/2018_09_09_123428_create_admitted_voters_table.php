<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmittedVotersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admitted_voters', function (Blueprint $table) {
			$table->integer('voting_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->primary(['voting_id', 'user_id']);
			
			$table->foreign('voting_id')
				->references('id')->on('votings')
				->onUpdate('cascade')
				->onDelete('cascade');
			$table->foreign('user_id')
				->references('id')->on('users')
				->onUpdate('cascade')
				->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admittedvoters');
    }
}
