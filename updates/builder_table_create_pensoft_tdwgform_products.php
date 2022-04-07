<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftTdwgformProducts extends Migration
{
    public function up()
    {
        Schema::create('pensoft_tdwgform_products', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('name');
            $table->bigInteger('product_id');
            $table->string('ticket');
            $table->string('type');
            $table->date('early_booking_date');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_tdwgform_products');
    }
}
