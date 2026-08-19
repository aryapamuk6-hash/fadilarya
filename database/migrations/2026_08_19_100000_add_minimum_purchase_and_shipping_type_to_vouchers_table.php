<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->decimal('min_purchase', 12, 2)->default(0)->after('value');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE vouchers MODIFY type ENUM('percentage', 'fixed', 'free_shipping') NOT NULL");
        }
    }

    public function down(): void
    {
        DB::table('vouchers')->where('type', 'free_shipping')->update(['type' => 'fixed']);
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE vouchers MODIFY type ENUM('percentage', 'fixed') NOT NULL");
        }

        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('min_purchase');
        });
    }
};
