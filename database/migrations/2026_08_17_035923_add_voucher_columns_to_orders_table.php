<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (
            Blueprint $table
        ) {

            $table->foreignId('voucher_id')
                ->nullable()
                ->after('user_id')
                ->constrained();

            $table->decimal(
                'discount',
                12,
                2
            )->default(0);

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (
            Blueprint $table
        ) {

            $table->dropConstrainedForeignId(
                'voucher_id'
            );

            $table->dropColumn(
                'discount'
            );

        });
    }
};