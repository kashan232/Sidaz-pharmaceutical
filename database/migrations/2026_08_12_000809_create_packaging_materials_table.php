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
        Schema::create('packaging_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('packaging_type'); // Bottle, Box, Cap, Label, Carton, Seal, Wrapper, Other
            $table->string('variant')->nullable();
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('department_id');
            $table->decimal('capacity', 15, 4)->nullable();
            $table->unsignedBigInteger('capacity_unit_id')->nullable();
            $table->decimal('min_stock', 15, 4)->default(0);
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('unit_id')->references('id')->on('units')->onDelete('restrict');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('capacity_unit_id')->references('id')->on('units')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packaging_materials');
    }
};
