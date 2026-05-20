<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('ticket_category', ['free', 'startup', 'investor', 'company', 'hackathon'])->default('free');
            $table->text('special_requirements')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'ticket_category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
