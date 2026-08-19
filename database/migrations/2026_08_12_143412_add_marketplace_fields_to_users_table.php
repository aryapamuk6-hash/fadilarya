<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('avatar')->nullable()->after('role');

            $table->decimal('balance', 12, 2)
                ->default(0)
                ->after('avatar');

            $table->integer('warning_count')
                ->default(0)
                ->after('balance');

            $table->enum('seller_status', [
                'none',
                'pending',
                'approved',
                'rejected'
            ])->default('none')->after('warning_count');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'avatar',
                'balance',
                'warning_count',
                'seller_status'
            ]);
        });
    }
};