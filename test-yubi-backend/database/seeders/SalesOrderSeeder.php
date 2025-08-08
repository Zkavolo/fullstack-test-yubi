<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SalesOrder;
use App\Models\SoDt;

class SalesOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salesOrder = SalesOrder::create([
            'so_number'      => 'SO-2025-0001',
            'so_date'        => '2025-04-17',
            'ship_date'      => '2025-04-24',
            'customer_id'    => 1,
            'currency_id'    => 1,
            'order_type'     => 1,
            'status'         => 'open',
            'vat_id'         => 0,
            'pph23_id'       => 0,
            'ship_dest'      => 'Buyer 1 Address',
            'discount_value' => 0,
            'discount_type'  => null,
            'si_total_am'    => 11400000,
            'sa_total_am'    => 11400000,
            'grand_total'    => 11400000,
        ]);

        $details = [
            [
                'product_uuid' => 'uuid-pc-server-a',
                'ref_type'     => 'Products',
                'quantity'     => 1,
                'price'        => 10000000,
                'disc_perc'    => 0,
                'disc_am'      => 0,
                'total_am'     => 10000000,
                'remark'       => ''
            ],
            [
                'product_uuid' => 'uuid-psu-500w',
                'ref_type'     => 'Products',
                'quantity'     => 4,
                'price'        => 350000,
                'disc_perc'    => 0,
                'disc_am'      => 0,
                'total_am'     => 1400000,
                'remark'       => ''
            ]
        ];

        foreach ($details as $item) {
            SoDt::create([
                'sales_order_id' => $salesOrder->id,
                ...$item
            ]);
        }
    }
}
