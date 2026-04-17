<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('seller_packages', 'showcase_post_limit')) {
            Schema::table('seller_packages', function (Blueprint $table) {
                $table->unsignedInteger('showcase_post_limit')->nullable()->after('product_upload_limit');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('seller_packages', 'showcase_post_limit')) {
            Schema::table('seller_packages', function (Blueprint $table) {
                $table->dropColumn('showcase_post_limit');
            });
        }
    }
};