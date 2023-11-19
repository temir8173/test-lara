<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('gateway');
            $table->integer('payment_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status');
            $table->integer('amount');
            $table->integer('amount_paid');
            $table->json('additional');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['gateway', 'payment_id']);
        });

        User::create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('test'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
