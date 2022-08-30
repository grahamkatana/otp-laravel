<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('otp')->unique();
            $table->boolean('is_valid')->default(true);
            $table->string('expires_in')->default(date("m/d/Y h:i:s a",strtotime("+30 seconds")));
            $table->foreignId('requester_id')->references('id')->on('requesters')->onDelete('cascade');
            $table->unsignedBigInteger('current_requests_count')->default(0);
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
        Schema::dropIfExists('requests');
    }
}
