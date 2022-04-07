<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformDiscountOptions extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_discount_options', function($table)
        {
            $table->string('amount_virtual')->nullable();
            $table->string('currency')->default('Euro');
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_discount_options', function($table)
        {
            $table->dropColumn('amount_virtual');
            $table->dropColumn('currency');
        });
    }
}
