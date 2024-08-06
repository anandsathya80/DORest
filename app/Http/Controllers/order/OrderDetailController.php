<?php

namespace App\Http\Controllers\order;

use App\Http\Controllers\Controller;
use App\Models\Foods;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderDetail = OrderDetail::all();
        $formattedorder = $orderDetail->map(function ($orderDetail) {
            return $this->formatOrderDetail($orderDetail);
        });

        return response()->json(['orderDetail' => $formattedorder,]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $orderDetail = OrderDetail::create([
            'order_id' => $request->input('order_id'),
            'food_id' => $request->input('food_id'),
            'food_qty' => $request->input('food_qty'),
        ]);

        return response()->json([$orderDetail, 'created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
