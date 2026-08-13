<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormulationPackagingMaterial extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function formulation()
    {
        return $this->belongsTo(Formulation::class);
    }

    public function packagingMaterial()
    {
        return $this->belongsTo(PackagingMaterial::class);
    }
}
