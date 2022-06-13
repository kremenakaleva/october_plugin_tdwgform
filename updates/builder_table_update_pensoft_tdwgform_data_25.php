<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformData25 extends Migration
{
	public function up()
	{
		Schema::table('pensoft_tdwgform_data', function($table)
		{
			$table->boolean('add_tshirt')->nullable();
			$table->string('tshirt_type')->nullable();
			$table->string('tshirt_size')->nullable();
		});
	}

	public function down()
	{
		Schema::table('pensoft_tdwgform_data', function($table)
		{
			$table->dropColumn('add_tshirt');
			$table->dropColumn('tshirt_type');
			$table->dropColumn('tshirt_size');
		});
	}
}
