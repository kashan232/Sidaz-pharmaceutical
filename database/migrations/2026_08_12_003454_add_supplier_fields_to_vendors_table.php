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
        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'company_name')) {
                $table->string('company_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('vendors', 'contact_person')) {
                $table->string('contact_person')->nullable()->after('company_name');
            }
            if (!Schema::hasColumn('vendors', 'ntn_number')) {
                $table->string('ntn_number')->nullable()->after('address');
            }
            if (!Schema::hasColumn('vendors', 'payment_terms')) {
                $table->string('payment_terms')->nullable()->after('ntn_number');
            }
            if (!Schema::hasColumn('vendors', 'credit_limit')) {
                $table->decimal('credit_limit', 15, 2)->default(0)->after('payment_terms');
            }
            if (!Schema::hasColumn('vendors', 'status')) {
                $table->boolean('status')->default(1)->after('opening_balance');
            }
            if (!Schema::hasColumn('vendors', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            //
        });
    }
};
