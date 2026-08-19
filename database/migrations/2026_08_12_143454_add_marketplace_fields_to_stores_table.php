<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {

            $table->string('slug')
                ->unique()
                ->after('name');

            $table->string('banner')
                ->nullable()
                ->after('logo');

            $table->text('address')
                ->nullable()
                ->after('description');

            $table->integer('total_reviews')
                ->default(0)
                ->after('total_sales');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {

            $table->dropColumn([
                'slug',
                'banner',
                'address',
                'total_reviews'
            ]);
        });
    }
};