<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostalParticipationLookupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postal_participation_lookups', function (Blueprint $table) {
            $table->id();
            $table->string('postal', 10)->unique();
            $table->integer('num_responder')->default(0); //number of responders within 2.5km of postal code
            $table->integer('num_inneed')->default(0); //number of people in need within 2.5km of postal code
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
        Schema::dropIfExists('postal_participation_lookups');
    }
}
