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
        Schema::create('material_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_purchase_id');
            
            // Morph relation for item
            $table->string('item_type'); // App\Models\RawMaterial or App\Models\PackagingMaterial
            $table->unsignedBigInteger('item_id');
            
            $table->decimal('qty', 15, 4);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            
            $table->timestamps();
            
            $table->foreign('material_purchase_id')->references('id')->on('material_purchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_purchase_items');
    }
};
