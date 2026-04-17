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
        Schema::create('ac_abandon_emails', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('ac_abandoned_cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ac_email_notification_id')->constrained()->cascadeOnDelete();
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('opened_at')->nullable();
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
        Schema::dropIfExists('ac_abandon_emails');
    }
};
