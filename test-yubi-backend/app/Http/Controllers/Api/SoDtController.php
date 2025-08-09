<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\SoDt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SoDtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SalesOrder $salesOrder)
    {
        return response()->json([
            'message' => 'List SO Detail',
            'data' => $salesOrder->soDts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SalesOrder $salesOrder)
    {
        $validator = Validator::make($request->all(), [
            'product_uuid' => 'required|string',
            'ref_type' => 'nullable|in:open,Products,Services',
            'disc_perc' => 'nullable|numeric',
            'disc_am' => 'nullable|numeric',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $disc_perc = $data['disc_perc'] ?? 0;
        $disc_am = $data['disc_am'] ?? 0;

        if ($disc_perc > 0 && $disc_am == 0) {
            $disc_am = ($data['quantity'] * $data['price']) * ($disc_perc / 100);
        } else if ($disc_am > 0 && $disc_perc == 0) {
            $disc_perc = ($disc_am / ($data['quantity'] * $data['price'])) * 100;
        } else if ($disc_perc > 0 && $disc_am > 0) {
            // akan diprioritaskan disc_perc
            $disc_am = ($data['quantity'] * $data['price']) * ($disc_perc / 100);
        } else {
            $disc_perc = 0;
            $disc_am = 0;
        }

        $total_am = ($data['quantity'] * $data['price']) - $disc_am;

        $detail = SoDt::create([
            ...$data,
            'sales_order_id' => $salesOrder->id,
            'disc_am' => $disc_am,
            'total_am' => $total_am,
        ]);

        // insert langsung ke nilai pada Sales Order biar tidak hitung ulang
        $si_total_am = SoDt::where('sales_order_id', $salesOrder->id)
            ->sum(DB::raw('quantity * price'));

        $sa_total_am = SoDt::where('sales_order_id', $salesOrder->id)
            ->sum('total_am');

        $discount_value = SoDt::where('sales_order_id', $salesOrder->id)
            ->sum('disc_am');

        $header_discount = 0;
        if ($salesOrder->discount_type === 'percentage') {
            $header_discount = $sa_total_am * ($salesOrder->discount_value / 100);
        } elseif ($salesOrder->discount_type === 'nominal') {
            $header_discount = $salesOrder->discount_value ?? 0;
        }

        $grand_total = $sa_total_am - $header_discount;
    
        $salesOrder->update([
            'si_total_am' => $si_total_am,
            'sa_total_am' => $sa_total_am,
            'discount_value' => $discount_value,
            'grand_total' => $grand_total,
        ]);

        return response()->json([
            'message' => 'SO Detail berhasil dibuat dan total di Sales Order diperbarui.',
            'data' => $detail
        ], 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(SalesOrder $salesOrder, $id)
    {
        $detail = SoDt::where('sales_order_id', $salesOrder->id)->find($id);

        if (!$detail) {
            return response()->json(['message' => 'SO Detail tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail ditemukan',
            'data' => $detail
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesOrder $salesOrder, $id)
    {
        $detail = SoDt::where('sales_order_id', $salesOrder->id)->find($id);

        if (!$detail) {
            return response()->json(['message' => 'SO Detail tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'product_uuid' => 'sometimes|required|string',
            'ref_type' => 'nullable|in:open,Products,Services',
            'disc_perc' => 'nullable|numeric',
            'disc_am' => 'nullable|numeric',
            'quantity' => 'sometimes|required|numeric',
            'price' => 'sometimes|required|numeric',
            'remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $quantity   = $data['quantity'] ?? $detail->quantity;
        $price      = $data['price'] ?? $detail->price;
        $disc_perc  = $data['disc_perc'] ?? $detail->disc_perc ?? 0;
        $disc_am    = $data['disc_am'] ?? $detail->disc_am ?? 0;

        if ($disc_perc > 0 && $disc_am == 0) {
            $disc_am = ($quantity * $price) * ($disc_perc / 100);
        } elseif ($disc_am > 0 && $disc_perc == 0) {
            $disc_perc = ($disc_am / ($quantity * $price)) * 100;
        } elseif ($disc_perc > 0 && $disc_am > 0) {
            $disc_am = ($quantity * $price) * ($disc_perc / 100);
        } else {
            $disc_perc = 0;
            $disc_am = 0;
        }

        $total_am = ($quantity * $price) - $disc_am;

        $detail->update([
            ...$data,
            'quantity'   => $quantity,
            'price'      => $price,
            'disc_perc'  => $disc_perc,
            'disc_am'    => $disc_am,
            'total_am'   => $total_am
        ]);

        $si_total_am = SoDt::where('sales_order_id', $salesOrder->id)
            ->sum(DB::raw('quantity * price'));

        $sa_total_am = SoDt::where('sales_order_id', $salesOrder->id)
            ->sum('total_am');

        $discount_value = SoDt::where('sales_order_id', $salesOrder->id)
            ->sum('disc_am');

        $header_discount = 0;
        if ($salesOrder->discount_type === 'percentage') {
            $header_discount = $sa_total_am * ($salesOrder->discount_value / 100);
        } elseif ($salesOrder->discount_type === 'nominal') {
            $header_discount = $salesOrder->discount_value ?? 0;
        }

        $grand_total = $sa_total_am - $header_discount;
    
        $salesOrder->update([
            'si_total_am' => $si_total_am,
            'sa_total_am' => $sa_total_am,
            'discount_value' => $discount_value,
            'grand_total' => $grand_total,
        ]);

        return response()->json([
            'message' => 'SO Detail berhasil diperbarui dan total di Sales Order diperbarui.',
            'data' => $detail
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrder $salesOrder, $id)
    {
        $detail = SoDt::where('sales_order_id', $salesOrder->id)->find($id);

        if (!$detail) {
            return response()->json(['message' => 'SO Detail tidak ditemukan.'], 404);
        }

        $detail->delete();

        return response()->json(['message' => 'SO Detail berhasil dihapus.']);
    }
}
