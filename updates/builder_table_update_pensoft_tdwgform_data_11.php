<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformData11 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->boolean('help_others_has_invoice')->nullable()->default(false);
            $table->boolean('submission_completed')->default(false)->change();
            $table->boolean('accompanying_person_has_invoice')->nullable()->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->dropColumn('help_others_has_invoice');
            $table->boolean('submission_completed')->default(null)->change();
            $table->boolean('accompanying_person_has_invoice')->nullable(false)->default(null)->change();
        });
    }
}
