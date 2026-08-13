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
        Schema::create('material_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->date('purchase_date');
            $table->unsignedBigInteger('vendor_id');
            $table->string('purchase_type'); // Raw Material, Packaging, Mixed
            $table->string('payment_method'); // Cash, Credit, Bank, Cheque, Other
            $table->string('payment_status'); // Paid, Partial, Pending
            
            // Optional Fields
            $table->string('transport_name')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_contact')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->text('remarks')->nullable();
            
            // Financials
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('balance_amount', 15, 2)->default(0);
            
            $table->string('status')->default('completed'); // pending, completed
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_purchases');
    }
};
