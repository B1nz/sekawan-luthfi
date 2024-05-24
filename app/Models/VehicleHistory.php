<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleHistory extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'user_id',
        'driver_id',
        'order_id',
        'fuel_usage',
        'range',
        'start',
        'end',
    ];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver() {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
