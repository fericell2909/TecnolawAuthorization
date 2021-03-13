<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTecnolawRecoveryPasswordTable extends Migration
{
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $tableName = 'tecnolaw_recovery_password';

	/**
	 * Run the migrations.
	 * @table tecnolaw_recovery_password
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create($this->tableName, function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->bigIncrements('id');
			$table->unsignedBigInteger('user_id');
			$table->string('token', 150)->nullable();
			$table->text('user_agent')->nullable();
			$table->dateTime('expiration_date')->nullable();
			$table->string('email_send', 150)->nullable();

			$table->index(["user_id"], 'fk_recovery_password_users1_idx');


			$table->foreign('user_id', 'fk_recovery_password_users1_idx')
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

?>