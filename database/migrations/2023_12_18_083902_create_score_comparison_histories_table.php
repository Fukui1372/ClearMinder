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
            $table->unsignedInteger('self_overall_score');
            $table->unsignedInteger('list_score');
            $table->unsignedInteger('concentration');
            $table->unsignedInteger('relaxation');
            $table->unsignedInteger('planned_time_spending');
            $table->timestamp('comparison_date')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
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
