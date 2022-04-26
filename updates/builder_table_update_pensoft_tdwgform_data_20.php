<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformData20 extends Migration
{
	public function up()
	{
		/**
		 * Make sure that you use the \DB:: to call the DB class
		 */
		\DB::unprepared('CREATE TRIGGER before_insert_data
                          BEFORE INSERT ON pensoft_tdwgform_data
                          FOR EACH ROW
                          SET new.data_id = UNHEX(REPLACE(UUID(), "-","");');
	}

	public function down()
	{
		\DB::unprepared('DROP TRIGGER IF EXISTS before_insert_data ON pensoft_tdwgform_data;');
	}
}
