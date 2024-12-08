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
        Schema::create('tasktodos', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->unsignedBigInteger("position");
            $table->datetime("end_data")->nullable()->default(null);
            $table->text("description")->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasktodos');
    }
};
