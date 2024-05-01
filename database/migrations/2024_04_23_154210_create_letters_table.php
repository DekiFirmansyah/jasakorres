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
        Schema::create('letter_files', function (Blueprint $table) {
            $table->id();
            $table->string('letter_code');
            $table->string('file');
            $table->timestamp('created_at')->nullable();
        });
        
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('about');
            $table->string('purpose');
            $table->date('date');
            $table->unsignedBigInteger('file_id');
            $table->text('description');
            $table->timestamps();
            $table->foreign('file_id')->references('id')->on('letter_files')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_files');
        Schema::dropIfExists('letters');
    }
};