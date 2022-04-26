<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformData10 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->boolean('accompanying_person_has_invoice')->default(false);
            $table->boolean('submission_completed')->default(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->dropColumn('accompanying_person_has_invoice');
            $table->boolean('submission_completed')->default(null)->change();
        });
    }
}
