<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformDiscountOptions2 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_discount_options', function($table)
        {
            $table->string('currency', 255)->default('€')->change();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_discount_options', function($table)
        {
            $table->string('currency', 255)->default('Euro')->change();
        });
    }
}
