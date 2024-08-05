<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'customer_name',
        'status_order',
        'order_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
