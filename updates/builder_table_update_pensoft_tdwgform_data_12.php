<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformData12 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->string('slack_email')->nullable();
            $table->string('twitter')->nullable();
            $table->boolean('checkbox_code_of_conduct')->default(true);
            $table->boolean('checkbox_presenting')->default(true);
            $table->boolean('checkbox_agree')->default(true);
            $table->boolean('checkbox_media')->default(true);
            $table->boolean('checkbox_optional_abstract')->nullable()->default(false);
            $table->boolean('checkbox_optional_attend_welcome')->nullable()->default(false);
            $table->boolean('checkbox_optional_attend_excursion')->nullable()->default(false);
            $table->boolean('checkbox_optional_attend_conference')->nullable()->default(false);
            $table->boolean('checkbox_optional_contacted')->nullable()->default(false);
            $table->boolean('checkbox_optional_understand')->nullable()->default(false);
            $table->boolean('checkbox_optional_open_session')->nullable()->default(false);
            $table->boolean('checkbox_optional_agree_shared')->nullable()->default(false);
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->dropColumn('slack_email');
            $table->dropColumn('twitter');
            $table->dropColumn('checkbox_code_of_conduct');
            $table->dropColumn('checkbox_presenting');
            $table->dropColumn('checkbox_agree');
            $table->dropColumn('checkbox_media');
            $table->dropColumn('checkbox_optional_abstract');
            $table->dropColumn('checkbox_optional_attend_welcome');
            $table->dropColumn('checkbox_optional_attend_excursion');
            $table->dropColumn('checkbox_optional_attend_conference');
            $table->dropColumn('checkbox_optional_contacted');
            $table->dropColumn('checkbox_optional_understand');
            $table->dropColumn('checkbox_optional_open_session');
            $table->dropColumn('checkbox_optional_agree_shared');
        });
    }
}
