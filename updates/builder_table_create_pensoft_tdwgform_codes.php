<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftTdwgformCodes extends Migration
{
    public function up()
    {
        Schema::create('pensoft_tdwgform_codes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('code');
            $table->integer('value');
            $table->string('type')->default('€');
            $table->boolean('is_used')->nullable()->default(false);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_tdwgform_codes');
    }
}
