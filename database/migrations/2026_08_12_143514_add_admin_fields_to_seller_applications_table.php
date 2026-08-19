<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    // Guard: only modify table when it exists. Also avoid adding columns
    // twice if migration is re-run in a non-fresh environment.
    if (! Schema::hasTable('seller_applications')) {
        return;
    }

    // Add columns only when they don't already exist
    if (! Schema::hasColumn('seller_applications', 'admin_notes') ||
        ! Schema::hasColumn('seller_applications', 'reviewed_at') ||
        ! Schema::hasColumn('seller_applications', 'reviewed_by')) {

        Schema::table('seller_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('seller_applications', 'admin_notes')) {
                $table->text('admin_notes')->nullable();
            }

            if (! Schema::hasColumn('seller_applications', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable();
            }

            if (! Schema::hasColumn('seller_applications', 'reviewed_by')) {
                $table->foreignId('reviewed_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }
}
    public function down(): void
    {
        Schema::table('seller_applications', function (Blueprint $table) {

            $table->dropForeign(['reviewed_by']);

            $table->dropColumn([
                'admin_notes',
                'reviewed_at',
                'reviewed_by'
            ]);
        });
    }
};