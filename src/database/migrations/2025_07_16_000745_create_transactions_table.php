<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->string('stripe_session_id')->unique()->nullable()->comment('カード払いのみ');
            $table->integer('payment_method')->comment('1:コンビニ払い 2:カード払い');
            $table->string('shipping_postcode')->comment('配送先の郵便番号');
            $table->string('shipping_address')->comment('配送先の住所');
            $table->string('shipping_building')->nullable()->comment('配送先の建物名');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
