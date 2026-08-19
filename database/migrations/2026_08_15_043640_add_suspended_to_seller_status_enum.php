<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY seller_status
            ENUM(
                'none',
                'pending',
                'approved',
                'rejected',
                'suspended'
            )
            NOT NULL
            DEFAULT 'none'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY seller_status
            ENUM(
                'none',
                'pending',
                'approved',
                'rejected'
            )
            NOT NULL
            DEFAULT 'none'
        ");
    }
};