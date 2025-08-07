<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoDt extends Model
{
    protected $table = 'so_dts';

    protected $fillable = [
        'sales_order_id',
        'product_uuid',
        'ref_type',
        'disc_perc',
        'disc_am',
        'quantity',
        'price',
        'total_am',
        'remark',
    ];

   public $timestamps = false;

   public function salesOrder()
   {
       return $this->belongsTo(SalesOrder::class, 'sales_order_id');
   }
}
