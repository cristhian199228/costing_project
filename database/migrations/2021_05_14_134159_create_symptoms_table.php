<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSymptomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symptoms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attention_id')->unique()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->tinyInteger('cough');
            $table->tinyInteger('throat_pain');
            $table->tinyInteger('nasal_congestion');
            $table->tinyInteger('difficulty_breathing');
            $table->tinyInteger('fever');
            $table->tinyInteger('general_discomfort');
            $table->tinyInteger('diarrhea');
            $table->tinyInteger('nausea_vomiting');
            $table->tinyInteger('headache');
            $table->tinyInteger('irritability');
            $table->tinyInteger('short_breath');
            $table->tinyInteger('anosmia_ausegia');
            $table->text('medication_treat')->nullable();
            $table->text('immune_condition')->nullable();
            $table->text('others')->nullable();
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
        Schema::dropIfExists('symptoms');
    }
}
