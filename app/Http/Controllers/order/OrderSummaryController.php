<?php

namespace App\Http\Controllers\order;

use App\Http\Controllers\Controller;
use App\Models\Foods;
use App\Models\OrderDetail;
use App\Models\Orders;
use App\Models\OrderSummary;
use App\Models\PaymentMethods;
use App\Models\User;
use Illuminate\Http\Request;

class OrderSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderSummary = OrderSummary::all();
        return response()->json($orderSummary);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $orderSummary = OrderSummary::create([
            'order_id' => $request->input('order_id'),
            'user_id' => $request->input('user_id'),
            'payment_method_id' => $request->input('payment_method_id'),
            'price_total' => $request->input('price_total'),
        ]);

        return response()->json($orderSummary);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orderSummary = OrderSummary::where('order_id', $id)->latest()
            ->paginate(10);
        $formattedOrderSummary = $orderSummary->map(function ($id) {
            return $this->formatOrderSummary($id);
        });
        $detailOrder = OrderDetail::where('order_id', $id)
            ->latest()
            ->paginate(10);
        $formattedDetailOrder = $detailOrder->map(function ($id) {
            return $this->formatOrderDetail($id);
        });


        return response()->json([
            'order_summary' => $formattedOrderSummary,
            'detail_order' => $formattedDetailOrder,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function formatOrderSummary(OrderSummary $orders)
    {
        $serverName = User::find($orders->user_id);
        $customerName = Orders::find($orders->order_id);
        $paymnetMethod = PaymentMethods::find($orders->payment_method_id);
        return [
            'id' => $orders->id,
            'order_id' => $orders->order_id,
            'user_id' => $orders->user_id,
            'server_name' => $serverName ? $serverName->name : null,
            'customer_name' => $customerName ? $customerName->customer_name : null,
            'payment_method' => $paymnetMethod ? $paymnetMethod->payment_method_name : null,
            'price_total' => $orders->price_total,
            'print_time' => $orders->print_time,
            'created_at' => $orders->created_at,
            'updated_at' => $orders->updated_at
        ];
    }

    private function formatOrderDetail(OrderDetail $orderDetail)
    {
        $food = Foods::find($orderDetail->food_id);
        return [
            'id' => $orderDetail->id,
            'order_id' => $orderDetail->order_id,
            'food_id' => $orderDetail->food_id,
            'food_qty' => $orderDetail->food_qty,
            'food_name' => $food ? $food->name : null,
            'price' => $orderDetail->food_qty * $food->price,
            'created_at' => $orderDetail->created_at,
            'updated_at' => $orderDetail->updated_at
        ];
    }
}
