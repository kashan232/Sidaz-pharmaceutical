<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialPurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_purchase_id',
        'item_type',
        'item_id',
        'qty',
        'unit_price',
        'discount',
        'tax',
        'batch_no',
        'mfg_date',
        'exp_date',
        'subtotal',
    ];

    public function purchase()
    {
        return $this->belongsTo(MaterialPurchase::class, 'material_purchase_id');
    }

    public function item()
    {
        return $this->morphTo();
    }
}
