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
        Schema::create('products', function (Blueprint $table) {

    $table->id();

    $table->foreignId('store_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('category_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('name');

    $table->string('slug')->unique();

    $table->longText('description');

    $table->decimal('price',12,2);

    $table->integer('stock');

    $table->integer('sold_count')
          ->default(0);

    $table->string('image');

    $table->enum('status',[
        'active',
        'sold_out'
    ])->default('active');

    $table->timestamps();
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
