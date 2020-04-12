<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->tinyInteger('delivery_option')->default(0);
            $table->string('delivery_instruction')->nullable();
            $table->tinyInteger('payment_option')->default(0);
            $table->string('payment_other_description')->nullable();
            $table->string('other_instruction')->nullable();
            //TODO Move to shoplist
            $table->string('vendor_name')->nullable();
            //TODO Move to shoplist
            $table->string('vendor_address')->nullable();
            $table->integer('shoplist_id')->nullable();
            //$table->decimal('total_amount', 4, 2)->nullable();
            //$table->longText('chat');
            $table->json('chat')->nullable();
            $table->string('receipt_image')->nullable();
            $table->integer('responded_by')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->date('needed_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sos');
    }
}
