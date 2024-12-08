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
        Schema::create('task_to_do_ticket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->references("id")->on("tickets")->onDelete('cascade');
            $table->foreignId('task_to_do_id')->references("id")->on("tasktodos")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_to_do_ticket');
    }
};
