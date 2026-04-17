<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('showcases', 'collection_items_json')) {
            Schema::table('showcases', function (Blueprint $table) {
                $table->longText('collection_items_json')->nullable()->after('main_visual');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('showcases', 'collection_items_json')) {
            Schema::table('showcases', function (Blueprint $table) {
                $table->dropColumn('collection_items_json');
            });
        }
    }
};