<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

class CreateSosRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sos_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('sos_id');
            $table->boolean('is_hujo')->default(false);
            $table->date('needed_by')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('responded_by')->nullable();
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
        Schema::dropIfExists('sos_requests');
    }
}
