<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTecnolawRegionsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'tecnolaw_regions';

    /**
     * Run the migrations.
     * @table region
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

			$table->bigIncrements('id');
            $table->string('name', 200);

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
