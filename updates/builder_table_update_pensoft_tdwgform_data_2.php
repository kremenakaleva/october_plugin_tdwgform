<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformData2 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->smallInteger('accompanying_person')->nullable()->default(0);
            $table->smallInteger('help_others')->nullable()->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->dropColumn('accompanying_person');
            $table->dropColumn('help_others');
        });
    }
}
