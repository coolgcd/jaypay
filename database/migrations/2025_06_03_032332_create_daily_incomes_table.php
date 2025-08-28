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
    Schema::create('daily_incomes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('member_id');
        $table->decimal('amount', 10, 2); // invested amount
        $table->timestamp('start_date')->nullable();
        $table->timestamp('end_date')->nullable();
        $table->decimal('total_received', 10, 2)->default(0);
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
        Schema::dropIfExists('daily_incomes');
    }
};
