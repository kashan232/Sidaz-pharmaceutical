<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name', 'company_name', 'contact_person', 'email', 'phone', 
        'address', 'ntn_number', 'payment_terms', 'credit_limit', 
        'opening_balance', 'status'
    ]; 

      public function ledger()
    {
        return $this->hasOne(VendorLedger::class);
    }
}
