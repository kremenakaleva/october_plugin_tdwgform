<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformProducts extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_products', function($table)
        {
            $table->integer('ticket_id');
            $table->dropColumn('ticket');
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_products', function($table)
        {
            $table->dropColumn('ticket_id');
            $table->string('ticket', 255);
        });
    }
}
