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
        Schema::table('units', function (Blueprint $table) {
            $table->string('short_code', 50)->after('name')->nullable();
            $table->string('unit_type', 50)->after('short_code')->nullable(); // Weight, Volume, Quantity
            $table->unsignedBigInteger('base_unit')->after('unit_type')->nullable();
            $table->decimal('conversion_factor', 15, 4)->default(1)->after('base_unit')->nullable();
            $table->boolean('status')->default(1)->after('conversion_factor');

            $table->foreign('base_unit')->references('id')->on('units')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropForeign(['base_unit']);
            $table->dropColumn(['short_code', 'unit_type', 'base_unit', 'conversion_factor', 'status']);
        });
    }
};
