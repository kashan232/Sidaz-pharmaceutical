<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialStockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_type',
        'item_id',
        'type',
        'qty',
        'ref_type',
        'ref_id',
        'note',
    ];

    public function item()
    {
        return $this->morphTo();
    }
}
