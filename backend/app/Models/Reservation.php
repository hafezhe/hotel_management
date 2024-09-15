<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['room_id','guest_id','start_date','end_date'];

    // Reservation model
    public function guest() {
        return $this->belongsTo(Guest::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }
}
