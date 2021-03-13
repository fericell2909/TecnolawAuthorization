<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTecnolawTokensTable extends Migration
{
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $tableName = 'tecnolaw_tokens';

	/**
	 * Run the migrations.
	 * @table tecnolaw_tokens
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
			$table->string('browser', 150)->nullable();
			$table->string('ip', 45)->nullable();
			$table->string('device', 200)->nullable();
			$table->string('os', 150)->nullable();
			$table->string('external_token', 200)->nullable();
			$table->dateTime('expiration_date')->nullable();

			$table->boolean('back_office')->default(0);
			
			$table->index(["user_id"], 'fk_token_user1_idx');


			$table->foreign('user_id', 'fk_token_user1_idx')
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