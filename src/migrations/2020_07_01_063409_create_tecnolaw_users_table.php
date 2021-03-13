<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTecnolawUsersTable extends Migration
{
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $tableName = 'tecnolaw_users';

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
			$table->integer('status')->nullable();
			$table->string('document', 45)->nullable();
			$table->string('phone', 45)->nullable();
			$table->string('name', 50)->nullable();
			$table->string('paternal_surname', 50)->nullable();
			$table->string('maternal_surname', 50)->nullable();
			$table->string('password', 100)->nullable();
			$table->string('username', 250)->nullable();
			$table->string('email', 150)->nullable();
			$table->string('language', 20)->nullable();
			$table->integer('gender')->nullable();
			$table->date('birth_date')->nullable();
			$table->integer('nationality')->nullable();
			$table->boolean('notifications')->default(false);
			$table->bigInteger('fb_id')->nullable();
			$table->text('fb_access_token')->nullable();

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
