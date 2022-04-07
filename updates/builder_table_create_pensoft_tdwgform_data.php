<?php namespace Pensoft\Tdwgform\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftTdwgformData extends Migration
{
    public function up()
    {
        Schema::create('pensoft_tdwgform_data', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('prefix');
            $table->string('suffix')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('first_name_tage')->nullable();
            $table->string('last_name_tag')->nullable();
            $table->string('institution')->nullable();
            $table->string('title')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->text('address');
            $table->string('city');
            $table->integer('country_id');
            $table->string('region')->nullable();
            $table->string('postal_code');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->text('comments')->nullable();
            $table->string('payment_options');
            $table->text('invoice_group_members')->nullable();
            $table->text('billing_details')->nullable();
            $table->string('invoice_email')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_tdwgform_data');
    }
}
