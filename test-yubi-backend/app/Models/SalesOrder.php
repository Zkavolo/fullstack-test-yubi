<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $table = 'sales_orders';

    protected $fillable = [
        'so_number',
        'so_date',
        'ship_date',
        'customer_id',
        'currency_id',
        'status',
        'order_type',
        'vat_id',
        'pph23_id',
        'ship_dest',
        'discount_value',
        'discount_type',
        'si_total_am',
        'sa_total_am',
        'grand_total',
    ];

    public $timestamps = false;

    public function soDts()
    {
        return $this->hasMany(SoDt::class, 'sales_order_id');
    }
}
