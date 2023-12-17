<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
     {
        Schema::create('score_comparison_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('self_overall_score');
            $table->integer('list_score');
            $table->integer('concentration');
            $table->integer('relaxation');
            $table->integer('plannd_time_spending');
            $table->timestamp('comparison_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('score_comparison_histories');
    }
};
