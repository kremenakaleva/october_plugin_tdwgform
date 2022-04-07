<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformData3 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->renameColumn('first_name_tage', 'first_name_tag');
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->renameColumn('first_name_tag', 'first_name_tage');
        });
    }
}
