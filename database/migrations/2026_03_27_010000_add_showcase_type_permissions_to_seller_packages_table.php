<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('seller_packages', 'allow_showcase_history')) {
                $table->boolean('allow_showcase_history')->default(true)->after('showcase_post_limit');
            }

            if (!Schema::hasColumn('seller_packages', 'allow_showcase_collection')) {
                $table->boolean('allow_showcase_collection')->default(true)->after('allow_showcase_history');
            }

            if (!Schema::hasColumn('seller_packages', 'allow_showcase_vitrin')) {
                $table->boolean('allow_showcase_vitrin')->default(true)->after('allow_showcase_collection');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seller_packages', function (Blueprint $table) {
            if (Schema::hasColumn('seller_packages', 'allow_showcase_vitrin')) {
                $table->dropColumn('allow_showcase_vitrin');
            }

            if (Schema::hasColumn('seller_packages', 'allow_showcase_collection')) {
                $table->dropColumn('allow_showcase_collection');
            }

            if (Schema::hasColumn('seller_packages', 'allow_showcase_history')) {
                $table->dropColumn('allow_showcase_history');
            }
        });
    }
};