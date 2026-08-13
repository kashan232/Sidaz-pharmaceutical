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
        Schema::table('material_purchases', function (Blueprint $table) {
            $table->decimal('subtotal', 15, 2)->default(0)->after('remarks');
            $table->decimal('total_discount', 15, 2)->default(0)->after('subtotal');
            $table->decimal('total_tax', 15, 2)->default(0)->after('total_discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_purchases', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'total_discount', 'total_tax']);
        });
    }
};
