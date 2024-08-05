<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foods extends Model
{
    use HasFactory;
    protected $fillable = [
        'food_type_id',
        'name',
        'url_picture',
        'availabiity',
        'price',
    ];

    public function foodType()
    {
        return $this->belongsTo(FoodType::class, 'food_type_id');
    }
}
