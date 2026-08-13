<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            if (!Schema::hasColumn('raw_materials', 'current_stock')) {
                $table->decimal('current_stock', 15, 4)->default(0)->after('type');
            }
        });

        Schema::table('packaging_materials', function (Blueprint $table) {
            if (!Schema::hasColumn('packaging_materials', 'current_stock')) {
                $table->decimal('current_stock', 15, 4)->default(0)->after('variant');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            if (Schema::hasColumn('raw_materials', 'current_stock')) {
                $table->dropColumn('current_stock');
            }
        });

        Schema::table('packaging_materials', function (Blueprint $table) {
            if (Schema::hasColumn('packaging_materials', 'current_stock')) {
                $table->dropColumn('current_stock');
            }
        });
    }
};
