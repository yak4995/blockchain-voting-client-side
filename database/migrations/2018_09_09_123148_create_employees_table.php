<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {			
			$table->integer('user_id')->unsigned();
			$table->integer('department_id')->unsigned();
			$table->integer('position_id')->unsigned();
			
			$table->foreign('user_id')
				->references('id')->on('users')
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
				
			$table->primary(['user_id', 'department_id', 'position_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
