<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderApproval extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'approver_id',
        'is_approved',
    ];

    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function approver() {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
