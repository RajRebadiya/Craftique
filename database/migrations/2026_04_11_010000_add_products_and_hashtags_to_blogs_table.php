<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('blogs')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'product_ids')) {
                $table->json('product_ids')->nullable()->after('description');
            }
            if (!Schema::hasColumn('blogs', 'hashtags')) {
                $table->string('hashtags', 255)->nullable()->after('product_ids');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('blogs')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table) {
            if (Schema::hasColumn('blogs', 'hashtags')) {
                $table->dropColumn('hashtags');
            }
            if (Schema::hasColumn('blogs', 'product_ids')) {
                $table->dropColumn('product_ids');
            }
        });
    }
};
