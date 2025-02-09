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
        Schema::create('detail_ujis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('uji_id');
            $table->unsignedBigInteger('parameter_id');
            $table->unsignedBigInteger('aturan_id');
            $table->decimal('cf_value', 5, 2);
            $table->foreign('uji_id')->references('id')->on('ujis')->onDelete('cascade');
            $table->foreign('parameter_id')->references('id')->on('parameters')->onDelete('cascade');
            $table->foreign('aturan_id')->references('id')->on('aturans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_ujis');
    }
};
