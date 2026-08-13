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
        Schema::create('formulation_packaging_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulation_id')->constrained('formulations')->onDelete('cascade');
            $table->foreignId('packaging_material_id')->constrained('packaging_materials')->onDelete('restrict');
            $table->decimal('quantity', 12, 4);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulation_packaging_materials');
    }
};
