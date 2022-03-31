<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLateTemperaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('late_temperatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attention_id')->unique()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->decimal('value', 3, 1);
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
        Schema::dropIfExists('late_temperatures');
    }
}
