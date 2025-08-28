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
    Schema::table('withdraw_requests', function (Blueprint $table) {
        $table->decimal('charge', 10, 2)->default(0)->after('amount');
        $table->decimal('final_amount', 10, 2)->default(0)->after('charge');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            //
        });
    }
};
