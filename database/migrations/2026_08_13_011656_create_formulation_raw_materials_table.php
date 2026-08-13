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
        Schema::create('formulation_raw_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulation_id')->constrained('formulations')->onDelete('cascade');
            $table->foreignId('raw_material_id')->constrained('raw_materials')->onDelete('restrict');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->decimal('quantity', 12, 4);
            $table->decimal('waste_percent', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulation_raw_materials');
    }
};
