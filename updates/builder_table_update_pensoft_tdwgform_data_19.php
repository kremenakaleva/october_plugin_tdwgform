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
		\DB::unprepared('CREATE TRIGGER before_insert_customers
                          BEFORE INSERT ON `megabank_highinterestloans_customers` 
                          FOR EACH ROW
                          SET new.uuid = uuid();');
	}

	public function down()
	{
		\DB::unprepared('DROP TRIGGER `before_insert_customers`');
	}
}
