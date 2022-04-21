<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpidemiologicalHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epidemiological_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attention_id')->unique()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->tinyInteger('traveled_14days_ago');
            $table->string('places_visited')->nullable();
            $table->string('conveyance')->nullable();
            $table->date('arrival_date')->nullable();
            $table->tinyInteger('close_contact');
            $table->tinyInteger('covid_conversation');
            $table->date('last_contact_date')->nullable();
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
        Schema::dropIfExists('epidemiological_histories');
    }
}
