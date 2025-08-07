<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SalesOrder;
use App\Models\SoDt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(SalesOrder::with('soDts')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'so_number' => 'required|string|unique:sales_orders,so_number',
            'so_date' => 'required|date',
            'ship_date' => 'required|date',
            'customer_id' => 'required|integer',
            'currency_id' => 'required|integer',
            'status' => 'required|in:open,closed,cancelled',
            'order_type' => 'required|integer',
            'vat_id' => 'required|integer',
            'pph23_id' => 'required|integer',
            'ship_dest' => 'nullable|string',
            'discount_value' => 'nullable|numeric',
            'discount_type' => 'nullable|in:percentage,nominal',

            'soDts' => 'required|array|min:1',
            'soDts.*.product_uuid' => 'required|string',
            'soDts.*.ref_type' => 'nullable|in:open,Products,Services',
            'soDts.*.disc_perc' => 'nullable|numeric',
            'soDts.*.disc_am' => 'nullable|numeric',
            'soDts.*.quantity' => 'nullable|numeric',
            'soDts.*.price' => 'required|numeric',
            'soDts.*.total_am' => 'nullable|numeric',
            'soDts.*.remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        DB::beginTransaction();

        try {
            $soDtRecords = [];
            $si_total_am = 0;

            foreach ($data['soDts'] as $dt) {
                $quantity = $dt['quantity'] ?? 0;
                $price = $dt['price'];
                $disc_perc = $dt['disc_perc'] ?? 0;
                $disc_am = $dt['disc_am'] ?? 0;
            
                $total_item = ($quantity * $price) - $disc_am;

                $si_total_am += $total_item;

                $soDtRecords[] = [
                    'product_uuid' => $dt['product_uuid'],
                    'ref_type'     => $dt['ref_type'] ?? 'open',
                    'disc_perc'    => $disc_perc,
                    'disc_am'      => $disc_am,
                    'quantity'     => $quantity,
                    'price'        => $price,
                    'total_am'     => $total_item,
                    'remark'       => $dt['remark'] ?? null,
                ];
            }

            $discount_value = $data['discount_value'] ?? 0;
            $discount_type = $data['discount_type'] ?? null;
            $sa_total_am = $si_total_am;

            if ($discount_type === 'percentage') {
                $sa_total_am -= ($si_total_am * ($discount_value / 100));
            } elseif ($discount_type === 'nominal') {
                $sa_total_am -= $discount_value;
            }

            $grand_total = $sa_total_am;

            $order = SalesOrder::create([
                'so_number'      => $data['so_number'],
                'so_date'        => $data['so_date'],
                'ship_date'      => $data['ship_date'],
                'customer_id'    => $data['customer_id'],
                'currency_id'    => $data['currency_id'],
                'status'         => $data['status'],
                'order_type'     => $data['order_type'],
                'vat_id'         => $data['vat_id'],
                'pph23_id'       => $data['pph23_id'],
                'ship_dest'      => $data['ship_dest'] ?? null,
                'si_total_am'    => $si_total_am,
                'sa_total_am'    => $sa_total_am,
                'grand_total'    => $grand_total,
                'discount_value' => $discount_value,
                'discount_type'  => $discount_type,
            ]);

            foreach ($soDtRecords as $dt) {
                SoDt::create(array_merge($dt, [
                    'sales_order_id' => $order->id,
                ]));
            }

            DB::commit();

            return response()->json([
                'message' => 'Sales Order berhasil disimpan.',
                'data' => $order->load('soDts')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan Sales Order: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan Sales Order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = SalesOrder::with('soDts')->find($id);
        if (!$order) {
            return response()->json(['message' => 'Sales Order tidak ditemukan.'], 404);
        }
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = SalesOrder::find($id);
        if (!$order) {
            return response()->json(['message' => 'Sales Order tidak ditemukan.'], 404);
        }

        // Set so_number agar tidak terkena unique check
        $request->merge(['so_number' => $order->so_number]);

        $validator = Validator::make($request->all(), [
            'so_number' => 'sometimes|required|string|unique:sales_orders,so_number,' . $order->id,
            'so_date' => 'sometimes|required|date',
            'ship_date' => 'sometimes|required|date',
            'customer_id' => 'sometimes|required|integer',
            'currency_id' => 'sometimes|required|integer',
            'status' => 'sometimes|required|in:open,closed,cancelled',
            'order_type' => 'sometimes|required|integer',
            'vat_id' => 'sometimes|required|integer',
            'pph23_id' => 'sometimes|required|integer',
            'ship_dest' => 'nullable|string',
            'discount_value' => 'nullable|numeric',
            'discount_type' => 'nullable|in:percentage,nominal',

            'soDts' => 'sometimes|array|min:1',
            'soDts.*.product_uuid' => 'required_with:soDts|string',
            'soDts.*.ref_type' => 'nullable|in:open,Products,Services',
            'soDts.*.disc_perc' => 'nullable|numeric',
            'soDts.*.disc_am' => 'nullable|numeric',
            'soDts.*.quantity' => 'nullable|numeric',
            'soDts.*.price' => 'required_with:soDts|numeric',
            'soDts.*.total_am' => 'nullable|numeric',
            'soDts.*.remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        DB::beginTransaction();

        try {
            $updateFields = array_filter($data, function ($key) {
                return $key !== 'soDts';
            }, ARRAY_FILTER_USE_KEY);

            $order->fill($updateFields);

            $si_total_am = $order->si_total_am;
            $sa_total_am = $order->sa_total_am;
            $grand_total = $order->grand_total;

            if (!empty($data['soDts'])) {
                // Hapus detail lama
                SoDt::where('sales_order_id', $order->id)->delete();

                $soDtRecords = [];
                $si_total_am = 0;

                foreach ($data['soDts'] as $dt) {
                    $quantity = $dt['quantity'] ?? 0;
                    $price = $dt['price'];
                    $disc_am = $dt['disc_am'] ?? 0;

                    $total_item = ($quantity * $price) - $disc_am;
                    $si_total_am += $total_item;

                    $soDtRecords[] = [
                        'sales_order_id' => $order->id,
                        'product_uuid'   => $dt['product_uuid'],
                        'ref_type'       => $dt['ref_type'] ?? 'open',
                        'disc_perc'      => $dt['disc_perc'] ?? 0,
                        'disc_am'        => $disc_am,
                        'quantity'       => $quantity,
                        'price'          => $price,
                        'total_am'       => $total_item,
                        'remark'         => $dt['remark'] ?? null,
                    ];
                }

                // Insert detail baru
                SoDt::insert($soDtRecords);

                // Hitung ulang diskon dan total jika perlu
                $discount_value = $data['discount_value'] ?? $order->discount_value ?? 0;
                $discount_type = $data['discount_type'] ?? $order->discount_type ?? null;

                $sa_total_am = $si_total_am;

                if ($discount_type === 'percentage') {
                    $sa_total_am -= ($si_total_am * ($discount_value / 100));
                } elseif ($discount_type === 'nominal') {
                    $sa_total_am -= $discount_value;
                }

                $grand_total = $sa_total_am;

                $order->si_total_am = $si_total_am;
                $order->sa_total_am = $sa_total_am;
                $order->grand_total = $grand_total;
            }

            $order->save();

            DB::commit();

            return response()->json([
                'message' => 'Sales Order berhasil diupdate.',
                'data' => $order->load('soDts')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update Sales Order: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat mengupdate Sales Order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = SalesOrder::find($id);
        if (!$order) {
            return response()->json(['message' => 'Sales Order tidak ditemukan.'], 404);
        }

        DB::beginTransaction();
        try {
            SoDt::where('sales_order_id', $id)->delete();
            $order->delete();
            DB::commit();

            return response()->json(['message' => 'Sales Order berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menghapus Sales Order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
