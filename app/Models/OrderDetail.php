<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrderDetail extends Model
{
    use HasFactory;

    use HasUuids;
    protected $fillable = [
        'order_id',
        'food_id',
        'food_id',
        'food_qty',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function food()
    {
        return $this->belongsTo(Foods::class, 'food_id');
    }
}
