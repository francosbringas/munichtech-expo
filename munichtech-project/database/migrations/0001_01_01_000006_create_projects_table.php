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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('collaboration_request_id')->nullable()->constrained('collaboration_requests')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->enum('status', ['planning', 'active', 'paused', 'completed'])->default('planning');
            $table->string('company_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
