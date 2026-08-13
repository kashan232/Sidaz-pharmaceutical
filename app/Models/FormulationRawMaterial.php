<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormulationRawMaterial extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function formulation()
    {
        return $this->belongsTo(Formulation::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
