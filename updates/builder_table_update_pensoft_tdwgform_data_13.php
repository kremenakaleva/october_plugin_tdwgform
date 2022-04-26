<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformData13 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->string('data_id')->nullable();
            
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->dropColumn('data_id');
           
        });
    }
}
