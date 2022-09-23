<?php

use App\Models\Transaction;
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
        Schema::create('transaction_transaction', function (Blueprint $table) {
            $table->foreignIdFor(Transaction::class, 'allocated_from_id');
            $table->foreignIdFor(Transaction::class, 'allocated_to_id');
            $table->integer('amount')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_transaction');
    }
};
