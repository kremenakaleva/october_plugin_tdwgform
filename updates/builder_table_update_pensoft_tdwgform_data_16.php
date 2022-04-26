<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformData16 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->boolean('submission_completed')->default(false)->change();
            $table->boolean('help_others_has_invoice')->default(false)->change();
            $table->boolean('checkbox_optional_abstract')->default(false)->change();
            $table->boolean('checkbox_optional_attend_welcome')->default(false)->change();
            $table->boolean('checkbox_optional_attend_excursion')->default(false)->change();
            $table->boolean('checkbox_optional_attend_conference')->default(false)->change();
            $table->boolean('checkbox_optional_contacted')->default(false)->change();
            $table->boolean('checkbox_optional_understand')->default(false)->change();
            $table->boolean('checkbox_optional_open_session')->default(false)->change();
            $table->boolean('checkbox_optional_agree_shared')->default(false)->change();
            $table->dropColumn('data_id');
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->boolean('submission_completed')->default(null)->change();
            $table->boolean('help_others_has_invoice')->default(null)->change();
            $table->boolean('checkbox_optional_abstract')->default(null)->change();
            $table->boolean('checkbox_optional_attend_welcome')->default(null)->change();
            $table->boolean('checkbox_optional_attend_excursion')->default(null)->change();
            $table->boolean('checkbox_optional_attend_conference')->default(null)->change();
            $table->boolean('checkbox_optional_contacted')->default(null)->change();
            $table->boolean('checkbox_optional_understand')->default(null)->change();
            $table->boolean('checkbox_optional_open_session')->default(null)->change();
            $table->boolean('checkbox_optional_agree_shared')->default(null)->change();
            $table->integer('data_id')->nullable();
        });
    }
}
