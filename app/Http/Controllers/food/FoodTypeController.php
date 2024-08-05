<?php

namespace App\Http\Controllers\food;

use App\Http\Controllers\Controller;
use App\Models\FoodType;
use Illuminate\Http\Request;

class FoodTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foodType = FoodType::all();
        $formattedFoodtype = $foodType->map(function ($foodType) {
            return $this->formatCustomer($foodType);
        });

        return response()->json(['foodType' => $formattedFoodtype,]);
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
        $foodType = FoodType::create([
            'food_type_name' => $request->input('food_type_name'),
        ]);

        return response()->json([$foodType, 'created successfully']);
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
}
