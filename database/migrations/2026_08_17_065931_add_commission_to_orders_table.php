<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'orders',
            function (
                Blueprint $table
            ) {

                $table->decimal(
                    'commission_amount',
                    12,
                    2
                )->default(0);

                $table->decimal(
                    'seller_income',
                    12,
                    2
                )->default(0);

            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'orders',
            function (
                Blueprint $table
            ) {

                $table->dropColumn([
                    'commission_amount',
                    'seller_income',
                ]);

            }
        );
    }
};