<?php
// database/migrations/xxxx_create_payment_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // member_payment, tpin_issued, income, withdrawal
            $table->string('sub_type')->nullable(); // admin_credit, member_debit, direct_income, level_income, etc.
            $table->string('member_id')->nullable(); // show_mem_id
            $table->decimal('amount', 10, 2);
            $table->enum('direction', ['credit', 'debit']); // credit = money in, debit = money out
            $table->string('source')->nullable(); // admin, system, referral, etc.
            $table->text('description')->nullable(); // Human readable description
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_logs');
    }
};