<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformProducts3 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_products', function($table)
        {
            $table->boolean('regular')->nullable()->default(true);
            $table->boolean('additional')->default(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_products', function($table)
        {
            $table->dropColumn('regular');
            $table->boolean('additional')->default(null)->change();
        });
    }
}
