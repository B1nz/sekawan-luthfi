<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'related_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function userRelate() {
        return $this->belongsTo(User::class, 'related_id')->withTrashed();
    }

    public function driver() {
        return $this->belongsTo(Driver::class, 'related_id')->withTrashed();
    }

    public function office() {
        return $this->belongsTo(Office::class, 'related_id')->withTrashed();
    }

    public function order() {
        return $this->belongsTo(Order::class, 'related_id')->withTrashed();
    }

    public function orderApproval() {
        return $this->belongsTo(OrderApproval::class, 'related_id')->withTrashed();
    }

    public function quarry() {
        return $this->belongsTo(Quarry::class, 'related_id')->withTrashed();
    }

    public function vehicle() {
        return $this->belongsTo(Vehicle::class, 'related_id')->withTrashed();
    }
}
