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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('ratings_count')->default(0);
            $table->unsignedBigInteger('ratings_sum')->default(0);
            $table->decimal('average', 3, 2)->default(0);

            $table->index('ratings_count');
            $table->index('average');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_ratings_count_index');
            $table->dropIndex('products_average_index');
            $table->dropColumn(['ratings_count', 'ratings_sum', 'average']);
        });
    }
};
