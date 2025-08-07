<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\SoDt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $total_am = ($data['quantity'] * $data['price']) - ($data['disc_am'] ?? 0);

        $detail = SoDt::create([
            ...$data,
            'sales_order_id' => $salesOrder->id,
            'total_am' => $total_am,
        ]);

        return response()->json([
            'message' => 'SO Detail berhasil dibuat.',
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

        $quantity = $data['quantity'] ?? $detail->quantity;
        $price = $data['price'] ?? $detail->price;
        $disc_am = $data['disc_am'] ?? $detail->disc_am ?? 0;
        $total_am = ($quantity * $price) - $disc_am;

        $detail->update([
            ...$data,
            'total_am' => $total_am
        ]);

        return response()->json([
            'message' => 'SO Detail berhasil diperbarui.',
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
