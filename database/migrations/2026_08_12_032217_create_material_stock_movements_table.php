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
        Schema::create('material_stock_movements', function (Blueprint $table) {
            $table->id();
            
            $table->string('item_type'); // App\Models\RawMaterial or App\Models\PackagingMaterial
            $table->unsignedBigInteger('item_id');
            
            $table->enum('type', ['in', 'out']);
            $table->decimal('qty', 15, 4);
            
            $table->string('ref_type'); // e.g., 'PURCHASE', 'MANUFACTURING', 'ADJUSTMENT'
            $table->unsignedBigInteger('ref_id'); // ID of the reference record
            
            $table->text('note')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_stock_movements');
    }
};
