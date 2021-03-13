<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTecnolawRolesUsersTable extends Migration
{
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $tableName = 'tecnolaw_roles_users';

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
			$table->unsignedBigInteger('assigned_by')->nullable();
			$table->unsignedBigInteger('removed_by')->nullable();
			$table->unsignedBigInteger('role_id');

			$table->index(['user_id'], 'fk_roles_users_users_idx');
			$table->foreign('user_id', 'fk_roles_users_users_idx')
				->references('id')->on('tecnolaw_users')
				->onDelete('no action')
				->onUpdate('no action');

			$table->index(['assigned_by'], 'fk_assigned_by_users_idx');
			$table->foreign('assigned_by', 'fk_assigned_by_users_idx')
				->references('id')->on('tecnolaw_users')
				->onDelete('no action')
				->onUpdate('no action');

			$table->index(['removed_by'], 'fk_removed_by_users_idx');
			$table->foreign('removed_by', 'fk_removed_by_users_idx')
				->references('id')->on('tecnolaw_users')
				->onDelete('no action')
				->onUpdate('no action');

			$table->index(['role_id'], 'fk_role_id_roles_idx');
			$table->foreign('role_id', 'fk_role_id_roles_idx')
				->references('id')->on('tecnolaw_roles')
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