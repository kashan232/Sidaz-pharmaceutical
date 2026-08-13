<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialPurchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_no',
        'purchase_date',
        'vendor_id',
        'purchase_type',
        'payment_method',
        'payment_status',
        'transport_name',
        'driver_name',
        'driver_contact',
        'vehicle_no',
        'transport_charges',
        'remarks',
        'subtotal',
        'total_discount',
        'total_tax',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'status',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function items()
    {
        return $this->hasMany(MaterialPurchaseItem::class, 'material_purchase_id');
    }
}
