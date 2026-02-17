<?php

use App\Models\Category;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class)->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->unsignedBigInteger('stock')->default(0);
            $table->string('image_url')->nullable();

            $table->unsignedBigInteger('ratings_count')->default(0);
            $table->unsignedBigInteger('ratings_sum')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);

            $table->timestamps();

            $table->index('ratings_count');
            $table->index('average_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
