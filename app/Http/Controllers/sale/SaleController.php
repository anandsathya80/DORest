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
    // start count by food
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
    // end count by food

    // start count by server
    public function countSalesByServer(
        string $id,
        string $startDate,
        string $endDate,
    ) {
        $totalSalesByServer = OrderSummary::where('user_id', $id)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();
        $countTotalOrder = count($totalSalesByServer);
        $formattedTotalByServer = $totalSalesByServer->map(function ($totalSalesByServer) {
            return $this->formatCountByServer($totalSalesByServer);
        });

        return response()->json([
            'total_sales_by_server' => $formattedTotalByServer,
            'total_sales' => $countTotalOrder,
            'from_date' => $startDate,
            'to_date' => $endDate,
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
    // end count by server

    // start count by date
    public function countByDate(
        string $startDate,
        string $endDate,
    ) {
        $totalSalesByDate = OrderSummary::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();
        $countTotalOrder = count($totalSalesByDate);
        $sumTotalOrderPrice = $totalSalesByDate->sum('price_total');
        $formatCountByDateInterval = $totalSalesByDate->map(function ($totalSalesByDate) {
            return $this->formatCountByDateInterval($totalSalesByDate);
        });

        return response()->json([
            'sales_by_date_interval' => $formatCountByDateInterval,
            'total_sales' => $countTotalOrder,
            'total_sales_price' => $sumTotalOrderPrice,
            'from_date' => $startDate,
            'to_date' => $endDate,
        ]);
    }
    private function formatCountByDateInterval(OrderSummary $orderSummary)
    {
        $order = Orders::find($orderSummary->order_id);
        $server = User::find($orderSummary->user_id);
        return [
            'order_id' => $orderSummary->order_id,
            'order_time' => $order ? $order->order_time : null,
            'server_name' => $server ? $server->name : null,
            'price_total' => $orderSummary->price_total,
        ];
    }
    // end count by date
}
