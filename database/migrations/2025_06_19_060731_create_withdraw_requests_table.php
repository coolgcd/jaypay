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
     Schema::create('withdraw_requests', function (Blueprint $table) {
    $table->id();
    $table->string('member_id'); // use show_mem_id
    $table->decimal('amount', 10, 2);
    $table->string('method')->nullable(); // Bank/UPI/etc.
    $table->text('remarks')->nullable(); // optional
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->timestamp('requested_at')->nullable();
    $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('withdraw_requests');
    }
};
