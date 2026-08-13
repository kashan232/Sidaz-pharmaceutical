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
        Schema::table('material_purchase_items', function (Blueprint $table) {
            $table->decimal('discount', 15, 2)->default(0)->after('unit_price');
            $table->decimal('tax', 15, 2)->default(0)->after('discount');
            $table->string('batch_no')->nullable()->after('tax');
            $table->date('mfg_date')->nullable()->after('batch_no');
            $table->date('exp_date')->nullable()->after('mfg_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_purchase_items', function (Blueprint $table) {
            $table->dropColumn(['discount', 'tax', 'batch_no', 'mfg_date', 'exp_date']);
        });
    }
};
