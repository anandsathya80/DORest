<?php

namespace App\Http\Controllers\sale;

use App\Http\Controllers\Controller;
use App\Models\Foods;
use App\Models\FoodType;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function countFood(
        string $startDate,
        string $endDate,
    ) {
        $totalFood = OrderDetail::groupBy('food_id')->selectRaw('sum(food_qty) as food_qty, food_id')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)->get();
        $formattedTotalFood = $totalFood->map(function ($totalFood) {
            return $this->formatCountFood($totalFood);
        });

        return response()->json([
            'totalFood' => $formattedTotalFood,
            'from_date' => $startDate,
            'to_date' => $endDate,
        ]);
    }

    private function formatCountFood(OrderDetail $orderDetail)
    {
        $food = Foods::find($orderDetail->food_id);
        $foodtype = FoodType::find($food->food_type_id);
        return [
            'food_type' => $foodtype ? $foodtype->food_type_name : null,
            'food_name' => $food ? $food->name : null,
            'qty' => $orderDetail->food_qty,
            'food_id' => $orderDetail->food_id,
        ];
    }
}
