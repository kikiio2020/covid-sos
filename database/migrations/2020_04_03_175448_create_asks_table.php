<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

class CreateAsksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asks', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('sos_id');
            $table->date('needed_by')->nullable();
            $table->string('special_instruction')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('responded_by')->nullable();
            //$table->json('chat')->default(new Expression('(JSON_ARRAY())'));
            $table->json('chat');
            $table->string('receipt_image')->nullable();
            $table->date('user_approved')->nullable();
            $table->date('responder_approved')->nullable();
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
        Schema::dropIfExists('asks');
    }
}
