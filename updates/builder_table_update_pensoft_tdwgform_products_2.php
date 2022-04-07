<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformProducts2 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_products', function($table)
        {
            $table->boolean('additional')->nullable()->default(false);
            $table->date('early_booking_date')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_products', function($table)
        {
            $table->dropColumn('additional');
            $table->date('early_booking_date')->nullable(false)->change();
        });
    }
}
