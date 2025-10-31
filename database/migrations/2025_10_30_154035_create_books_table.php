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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn', 20)->nullable()->unique();
            $table->string('publisher');
            $table->integer('publication_year');
            $table->integer('total_quantity')->default(1);
            $table->integer('available_quantity')->default(1);
            $table->timestamps();

            // Indexes
            $table->index(['title', 'author']);
            $table->index('publication_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
