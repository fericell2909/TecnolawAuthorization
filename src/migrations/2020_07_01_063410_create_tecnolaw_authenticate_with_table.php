<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTecnolawAuthenticateWithTable extends Migration
{
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $tableName = 'tecnolaw_authenticate_with';

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
            $table->increments('id');
            $table->string('display_name', 45)->nullable();
            $table->string('slug')->nullable();
            $table->string('client_id')->nullable();
            $table->string('key_secret')->nullable();
            $table->string('redirect_uri')->nullable();
            $table->tinyInteger('enabled')->nullable();

            $table->unique(["id"], 'id_UNIQUE');
            $table->timestamps();
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