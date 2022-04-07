<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformProducts4 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_products', function($table)
        {
            $table->boolean('accompanying_person')->nullable()->default(false);
            $table->boolean('help_others')->default(false);
            $table->dropColumn('additional');
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_products', function($table)
        {
            $table->dropColumn('accompanying_person');
            $table->dropColumn('help_others');
            $table->boolean('additional')->nullable();
        });
    }
}
