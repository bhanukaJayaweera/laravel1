<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDeletionRequest extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'order_id',
        'requested_by',
        'status',
        'requested_changes',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
