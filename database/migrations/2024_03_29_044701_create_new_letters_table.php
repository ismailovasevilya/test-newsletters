<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('text');
            $table->string('status')->default(\App\Enums\NewsLetterStatusEnum::INACTIVE);
            $table->boolean('immediate_send')->default(false);
            $table->unsignedInteger('days_before_birthday')->nullable();
            $table->time('time_to_send')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_letters');
    }
};
