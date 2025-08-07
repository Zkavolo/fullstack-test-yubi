<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();

            $table->string('so_number')->unique();
            $table->date('so_date');
            $table->date('ship_date');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('currency_id');
            $table->enum('status',['open', 'closed', 'cancelled'])->default('open');
            $table->unsignedBigInteger('order_type');
            $table->unsignedBigInteger('vat_id');
            $table->unsignedBigInteger('pph23_id');
            $table->string('ship_dest')->nullable();
            //sub amount
            $table->decimal('si_total_am', 15, 2)->default(0);
            //amount after discount
            $table->decimal('sa_total_am', 15, 2)->default(0);
            // grand amount
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('discount_value', 15, 2)->nullable();
            $table->enum('discount_type', ['percentage', 'nominal'])->nullable();
            $table->timestamps();

            // referened tapi pada front end
            // $table->unsignedBigInteger('customer_id');
            // $table->unsignedBigInteger('order_type_id');
            // $table->unsignedBigInteger('currency_id');
            // $table->unsignedBigInteger('warehouse_id');
            // $table->unsignedBigInteger('payment_id');
            // $table->unsignedBigInteger('vat_id');
            // $table->unsignedBigInteger('pph23_id');
            // $table->unsignedBigInteger('branch_id');
            // $table->string('rev_no');
            // $table->string('po_buyer_no')->nullable();
            // $table->string('po_buyer_no_ori')->nullable();
            // $table->string('sales_order_no')->unique();
            // $table->string('remark');
            // $table->date('ship_dest')->nullable();
            // $table->enum('status', ['open', 'closed', 'cancelled'])->default('open');
            // $table->decimal('exchange_rate', 15, 2)->default(0);
            // $table->decimal('vat_perc', 15, 2)->nullable();
            // $table->decimal('pph23_perc', 15, 2)->nullable();
            // $table->decimal('disc_am', 15, 2)->nullable();
            // $table->decimal('disc_perc', 5, 2)->nullable();
            // $table->decimal('disc_perc_am', 5, 2)->nullable();
            // $table->decimal('disc_final', 5, 2)->nullable();
            // $table->string('disc_type')->nullable();
            // $table->decimal('total_qty', 12, 2)->default(0);
            // $table->decimal('subtotal', 15, 2)->default(0);
            // $table->decimal('total_discount', 15, 2)->default(0);
            // $table->decimal('total_pph23', 15, 2)->default(0);
            // $table->decimal('total_vat', 15, 2)->default(0);
            // $table->decimal('grand_total', 15, 2)->default(0);
            // $table->decimal('qty_out', 12, 2)->default(0);
            // $table->decimal('si_total_am', 15, 2)->default(0);
            // $table->decimal('sa_total_am', 15, 2)->default(0);
            // $table->datetime('order_at')->nullable();
            // $table->datetime('shipping_at')->nullable();
            // $table->datetime('agree_at')->nullable();
            // $table->datetime('due_at')->nullable();
            // $table->string('expired_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
