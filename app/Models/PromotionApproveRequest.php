<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionApproveRequest extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'promotion_id',
        'requested_by',
        'status',
        'requested_changes',
    ];
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
