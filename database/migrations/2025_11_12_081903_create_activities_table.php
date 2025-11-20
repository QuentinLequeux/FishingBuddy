<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('specie_id')->constrained('species');
            $table->integer('size');
            $table->decimal('weight');
            $table->foreignId('lure_id')->constrained('lures');
            //$table->string('image');
            //$table->string('visibility');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

// TODO : Visibility ?, Lieu ?, Views ?, Photo ?
