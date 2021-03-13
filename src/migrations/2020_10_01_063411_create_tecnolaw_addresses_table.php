<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTecnolawAddressesTable extends Migration
{
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $tableName = 'tecnolaw_addresses';

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
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('commune_id');
			$table->unsignedBigInteger('region_id');

			$table->string('street', 200);
			$table->string('number', 50);

			// Optional
			$table->string('description', 400)->nullable();
			$table->string('department_number', 5)->nullable();
			$table->string('lat', 250)->nullable();
			$table->string('log', 250)->nullable();
			$table->string('postal_code', 50)->nullable();
			$table->boolean('main_default')->default(0);


			$table->index(['user_id'], 'fk_adresses_users_idx');
			$table->foreign('user_id', 'fk_adresses_users_idx')
				->references('id')->on('tecnolaw_users')
				->onDelete('no action')
				->onUpdate('no action');

			$table->index(['commune_id'], 'fk_commune_id_communes_idx');
			$table->foreign('commune_id', 'fk_commune_id_communes_idx')
				->references('id')->on('tecnolaw_communes')
				->onDelete('no action')
				->onUpdate('no action');

			$table->index(['region_id'], 'fk_region_id_regions_idx');
			$table->foreign('region_id', 'fk_region_id_regions_idx')
				->references('id')->on('tecnolaw_regions')
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