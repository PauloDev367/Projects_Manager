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
        Schema::table('tasktodos', function (Blueprint $table) {
            $table->unsignedBigInteger("column_id");
            $table->foreign("column_id")->references("id")->on("columns");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasktodos', function (Blueprint $table) {
            $table->dropForeign(['column_id']);
            $table->dropColumn('column_id'); 
        });
    }
};
