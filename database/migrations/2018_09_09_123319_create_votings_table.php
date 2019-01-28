<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votings', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
            $table->text('description');
            $table->boolean('is_published')->default(false);
			$table->timestamp('start_time');
			$table->timestamp('end_time');
            $table->timestamps();
			$table->integer('faculty_id')->unsigned()->nullable();
			$table->integer('department_id')->unsigned()->nullable();
			$table->integer('position_id')->unsigned()->nullable();
            
            $table->foreign('faculty_id')
				->references('id')->on('faculties')
				->onUpdate('cascade')
				->onDelete('cascade');
			$table->foreign('department_id')
				->references('id')->on('departments')
				->onUpdate('cascade')
				->onDelete('cascade');
			$table->foreign('position_id')
				->references('id')->on('positions')
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
        Schema::dropIfExists('votings');
    }
}
