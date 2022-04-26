<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformData15 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->integer('data_id')->nullable();
            
        });
    }
    
    public function down()
    {
        if (Schema::hasColumn('pensoft_tdwgform_data', 'data_id'))
            {
                Schema::table('pensoft_tdwgform_data', function($table)
                {
                    $table->dropColumn('data_id');
                   
                });
            }
    }
}
