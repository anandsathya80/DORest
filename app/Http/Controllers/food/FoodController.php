<?php

namespace App\Http\Controllers\food;

use App\Http\Controllers\Controller;
use App\Models\Foods;
use App\Models\FoodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use PHPUnit\Framework\Constraint\Count;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function respondUnauthorized()
    {
        return response()->json(['error' => 'Unauthenticated, kindly try again after authentication'], Response::HTTP_UNAUTHORIZED);
    }

    private function checkToken()
    {
        return Auth::check();
    }

    public function index()
    {
        if (!$this->checkToken()) {
            return $this->respondUnauthorized();
        }

        $food = Foods::all();
        $formattedFood = $food->map(function ($food) {
            return $this->formatFood($food);
        });
        $totalFood = Foods::count();
        $totalDessert = Foods::count();
        $totalBeverage = Foods::count();

        return response()
            ->json([
                'totalFood' => $totalFood,
                'totalBeverage' => $totalBeverage,
                'totalDessert' => $totalDessert,
                'F&B' => $formattedFood,
            ]);
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
        if ($request->hasFile('url_picture')) {
            $file = $request->file('url_picture');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/foods', $fileName, 'public');
        }

        $food = Foods::create([
            'food_type_id' => $request->input('food_type_id'),
            'name' => $request->input('name'),
            'url_picture' => $filePath,
            'availability' => $request->input('availability'),
            'price' => $request->input('price'),
        ]);

        return response()->json([$food, 'created successfully']);
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

    private function formatFood(Foods $food)
    {
        $foodType = FoodType::find($food->food_type_id);
        return [
            'id' => $food->id,
            'food_type_id' => $food->food_type_id,
            'type' => $foodType ? $foodType->food_type_name : null,
            'name' => $food->name,
            'url_picture' => $food->url_picture,
            'availability' => $food->availability,
            'price' => $food->price,
            'created_at' => $food->created_at,
            'updated_at' => $food->updated_at
        ];
    }
}
