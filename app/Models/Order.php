<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'approver_id',
        'quarry_id',
        'driver_id',
        'vehicle_id',
        'status',
        'start',
        'end',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver() {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function quarry() {
        return $this->belongsTo(Quarry::class, 'quarry_id');
    }

    public function driver() {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function vehicle() {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
