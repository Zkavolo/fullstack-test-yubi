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
        Schema::create('so_dts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sales_order_id')->constrained('sales_orders')->onDelete('cascade');
            $table->string('product_uuid');
            $table->enum('ref_type', ['open', 'Products', 'Services'])->default('open');
            $table->decimal('disc_perc')->default(0);
            $table->decimal('disc_am', 15, 2)->default(0);
            $table->decimal('quantity')->default(1);
            $table->decimal('price', 15, 2);
            $table->decimal('total_am', 15, 2)->default(0);
            $table->text('remark')->nullable();
            $table->timestamps();

            // referenced tapi pada front end

            // $table->string('product_uuid');
            // $table->unsignedBigInteger('sales_order_id');
            // $table->unsignedBigInteger('item_unit_id');
            // $table->unsignedBigInteger('vat_id');
            // $table->unsignedBigInteger('pph23_id');
            // $table->unsignedBigInteger('ref_id');
            // $table->unsignedBigInteger('item_id');
            // $table->string('ref_type');
            //ref_json
            // $table->string('item_type');
            //item_json
            // $table->string('gen_code');
            // $table->string('remark')->nullable();
            // $table->decimal('vat_perc', 15, 2)->nullable();
            // $table->decimal('vat_perc_am', 15, 2)->nullable();
            // $table->decimal('pph23_perc', 15, 2)->nullable();
            // $table->decimal('pph23_perc_am', 15, 2)->nullable();
            // $table->decimal('markup_perc', 15, 2)->nullable();
            // $table->decimal('markup_perc_am', 15, 2)->nullable();
            // $table->decimal('is_vat', 1, 0)->default(0);
            // $table->decimal('is_pph23', 1, 0)->default(0);
            // $table->decimal('is_lock_markup', 1, 0)->default(0);
            // $table->decimal('is_lock_price_sell', 1, 0)->default(0);
            // $table->decimal('qty_out', 12, 2)->default(0);
            // $table->decimal('qty', 12, 2)->default(0);
            // $table->decimal('price_sell', 15, 2);
            // $table->decimal('price_buy', 15, 2);
            // $table->decimal('subtotal_sell', 15, 2);
            // $table->decimal('subtotal_buy', 15, 2);
            // $table->decimal('disc_am', 15, 2)->nullable();
            // $table->decimal('disc_perc', 5, 2)->nullable();
            // $table->decimal('disc_perc_am', 5, 2)->nullable();
            // $table->decimal('disc_final', 5, 2)->nullable();
            // $table->string('disc_type')->nullable();
            // $table->decimal('total_am', 15, 2)->default(0);
            // $table->decimal('si_total_am', 15, 2)->default(0);
            // $table->decimal('sa_total_am', 15, 2)->default(0);
            // $table->decimal('total_dp', 15, 2)->default(0);
            // $table->decimal('history_total_dp', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so_dts');
    }
};
