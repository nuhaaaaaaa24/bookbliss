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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('book_name')->nullable();// Foreign key to users
            $table->tinyInteger('rating')->unsigned()->comment('Rating 1-5');
            $table->text('review_text')->nullable();
            $table->string('review_image')->nullable(); // Path to the uploaded image
            $table->timestamps();

            // Foreign key constraint (Assuming you have users table)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
