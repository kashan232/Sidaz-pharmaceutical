<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'short_code',
        'unit_type',
        'base_unit',
        'conversion_factor',
        'status',
    ];

    /**
     * Get the base unit for this unit.
     */
    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'base_unit');
    }

    /**
     * Get the sub units that use this unit as a base.
     */
    public function subUnits()
    {
        return $this->hasMany(Unit::class, 'base_unit');
    }

    /**
     * Get the raw materials that use this unit.
     */
    public function rawMaterials()
    {
        return $this->hasMany(RawMaterial::class);
    }

    /**
     * Get the packaging materials that use this unit for capacity or stock.
     */
    public function packagingMaterials()
    {
        return $this->hasMany(PackagingMaterial::class, 'unit_id');
    }
}
