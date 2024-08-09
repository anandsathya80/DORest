<?php

namespace App\Http\Controllers\sale;

use App\Http\Controllers\Controller;
use App\Models\Foods;
use App\Models\FoodType;
use App\Models\OrderDetail;
use App\Models\Orders;
use App\Models\OrderSummary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function countFood(
        string $startDate,
        string $endDate,
    ) {
        $totalFood = OrderDetail::groupBy('food_id')
            ->selectRaw('sum(food_qty) as food_qty, food_id')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)->get();
        $formattedTotalFood = $totalFood->map(function ($totalFood) {
            return $this->formatCountFood($totalFood);
        });

        $totalPrice = OrderDetail::join('foods', 'order_details.food_id', '=', 'foods.id')
            ->sum(DB::raw('order_details.food_qty * foods.price'));
        return response()->json([
            'totalFood' => $formattedTotalFood,
            'from_date' => $startDate,
            'to_date' => $endDate,
            'total_price_sales' => $totalPrice,
        ]);
    }

    private function formatCountFood(OrderDetail $orderDetail)
    {
        $food = Foods::find($orderDetail->food_id);
        $foodtype = FoodType::find($food->food_type_id);
        return [
            'food_type' => $foodtype ? $foodtype->food_type_name : null,
            'food_name' => $food ? $food->name : null,
            'food_price' => $food->price,
            'qty' => $orderDetail->food_qty,
            'food_id' => $orderDetail->food_id,
            'total_sales_per_food' => $orderDetail->food_qty * $food->price,
        ];
    }

    public function countSalesByServer(
        string $id,
        string $startDate,
        string $endDate,
    ) {
        $totalSalesByServer = OrderSummary::where('user_id', $id)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();
        $formattedTotalByServer = $totalSalesByServer->map(function ($totalSalesByServer) {
            return $this->formatCountByServer($totalSalesByServer);
        });

        return response()->json([
            'total_sales_by_server' => $formattedTotalByServer,
        ]);
    }

    private function formatCountByServer(OrderSummary $orderSummary)
    {
        $order = Orders::find($orderSummary->order_id);
        return [
            'order_id' => $orderSummary->order_id,
            'order_time' => $order ? $order->order_time : null,
            'price_total' => $orderSummary->price_total,
        ];
    }
}
