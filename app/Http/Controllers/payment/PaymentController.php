<?php

namespace App\Http\Controllers\payment;

use App\Http\Controllers\Controller;
use App\Models\OrderSummary;
use App\Models\PaymentMethods;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payment = PaymentMethods::all();

        return response()->json($payment);
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
        $payment = PaymentMethods::create([
            'payment_method_name' => $request->input('payment_method_name'),

        ]);
        return response()->json([$payment, 'created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        string $id,
        string $startDate,
        string $endDate,
    ) {
        $totalPayment = OrderSummary::where('payment_method_id', $id)
            ->whereDate('print_time', '>=', $startDate)
            ->whereDate('print_time', '<=', $endDate)->get();
        $sumPayment = $totalPayment->sum('price_total');
        $totalOrder = $totalPayment->count('order_id');

        return response()->json(
            [
                'order' => $totalPayment,
                'total_paymnet'  => $sumPayment,
                'total_order' => $totalOrder,
                'from_date' => $startDate,
                'to_date' => $endDate,
            ]
        );
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
}
