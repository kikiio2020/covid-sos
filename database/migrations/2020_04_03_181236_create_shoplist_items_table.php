<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoplistItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoplist_items', function (Blueprint $table) {
            $table->id();
            $table->integer('sos_id');
            $table->integer('item_id');
            $table->string('description')->nullable();
            $table->string('quantity');
            $table->decimal('max_dollar', 4, 2);
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
        Schema::dropIfExists('shoplist_items');
    }
}
