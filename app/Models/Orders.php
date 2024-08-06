<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Orders extends Model
{
    use HasFactory;
    use HasUuids;
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
