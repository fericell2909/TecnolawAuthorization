<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTecnolawRolesTable extends Migration
{
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $tableName = 'tecnolaw_roles';

	/**
	 * Run the migrations.
	 * @table users
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create($this->tableName, function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->bigIncrements('id');
			$table->string('name', 50)->nullable();
			$table->unsignedBigInteger('created_by');
			$table->integer('status')->nullable();
			$table->boolean('back_office')->default(0);


			$table->index(["created_by"], 'fk_created_by_users_idx');
			$table->foreign('created_by', 'fk_created_by_users_idx')
				->references('id')->on('tecnolaw_users')
				->onDelete('no action')
				->onUpdate('no action');

			$table->dateTime('created_at')->nullable();
			$table->dateTime('updated_at')->nullable();
			$table->dateTime('deleted_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists($this->tableName);
	}
}