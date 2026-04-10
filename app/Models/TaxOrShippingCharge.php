<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxOrShippingCharge extends Model
{
    // Add this line to match the table name in your migration exactly
    protected $table = 'tax_or_shipping_charge';

    protected $fillable = ['tax', 'shipping_charge', 'max_charge_for_shipping'];
}

