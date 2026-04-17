<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('showcases') || !Schema::hasColumn('showcases', 'type')) {
            return;
        }

        // Allow new showcase types like "launch" by converting to a generic string.
        DB::statement("ALTER TABLE `showcases` MODIFY COLUMN `type` VARCHAR(30) NULL");

        // Backfill any empty type rows created before the enum update.
        DB::table('showcases')
            ->where(function ($query) {
                $query->whereNull('type')
                    ->orWhere('type', '');
            })
            ->where(function ($query) {
                $query->whereNotNull('subtitle_gr')
                    ->orWhereNotNull('subtitle_en');
            })
            ->update(['type' => 'launch']);
    }

    public function down(): void
    {
        if (!Schema::hasTable('showcases') || !Schema::hasColumn('showcases', 'type')) {
            return;
        }

        // Keep it as a string on rollback to avoid enum mismatch errors.
        DB::statement("ALTER TABLE `showcases` MODIFY COLUMN `type` VARCHAR(30) NULL");
    }
};
