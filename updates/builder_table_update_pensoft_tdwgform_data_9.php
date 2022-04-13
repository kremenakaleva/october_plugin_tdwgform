<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftTdwgformData9 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->string('accompanying_person_name')->nullable();
            $table->string('address')->nullable(false)->unsigned(false)->default(null)->comment(null)->change();
            $table->boolean('submission_completed')->default(false)->change();
            $table->renameColumn('fax', 'address2');
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_tdwgform_data', function($table)
        {
            $table->dropColumn('accompanying_person_name');
            $table->text('address')->nullable(false)->unsigned(false)->default(null)->comment(null)->change();
            $table->boolean('submission_completed')->default(null)->change();
            $table->renameColumn('address2', 'fax');
        });
    }
}
