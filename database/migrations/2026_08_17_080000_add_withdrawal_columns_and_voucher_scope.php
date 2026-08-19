<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('withdrawals')) {
            Schema::table('withdrawals', function (Blueprint $table) {
                if (! Schema::hasColumn('withdrawals', 'commission_deduction')) {
                    $table->decimal('commission_deduction', 12, 2)->default(0)->after('amount');
                }

                if (! Schema::hasColumn('withdrawals', 'net_amount')) {
                    $table->decimal('net_amount', 12, 2)->default(0)->after('commission_deduction');
                }

                if (! Schema::hasColumn('withdrawals', 'proof')) {
                    $table->text('proof')->nullable()->after('admin_note');
                }
            });
        }

        if (Schema::hasTable('vouchers')) {
            Schema::table('vouchers', function (Blueprint $table) {
                if (! Schema::hasColumn('vouchers', 'scope')) {
                    $table->enum('scope', ['all', 'user'])->default('all')->after('value');
                }

                if (! Schema::hasColumn('vouchers', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('scope')->constrained()->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('withdrawals')) {
            Schema::table('withdrawals', function (Blueprint $table) {
                if (Schema::hasColumn('withdrawals', 'commission_deduction')) {
                    $table->dropColumn('commission_deduction');
                }

                if (Schema::hasColumn('withdrawals', 'net_amount')) {
                    $table->dropColumn('net_amount');
                }

                if (Schema::hasColumn('withdrawals', 'proof')) {
                    $table->dropColumn('proof');
                }
            });
        }

        if (Schema::hasTable('vouchers')) {
            Schema::table('vouchers', function (Blueprint $table) {
                if (Schema::hasColumn('vouchers', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }

                if (Schema::hasColumn('vouchers', 'scope')) {
                    $table->dropColumn('scope');
                }
            });
        }
    }
};
