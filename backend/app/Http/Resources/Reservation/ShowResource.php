<?php

namespace App\Http\Resources\Reservation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Room\ListResource as RoomResource;
use App\Http\Resources\Guest\ListResource as GuestResource;

class ShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'room_id' => $this->room_id,
            'guest_id' => $this->guest_id,
            'room' => !is_null($this->room) ? RoomResource::make($this->room) : [],
            'guest' => !is_null($this->guest) ? GuestResource::make($this->guest) : [],
            'fname' => !is_null($this->guest) ? $this->guest->fname : [],
            'lname' => !is_null($this->guest) ? $this->guest->lname : [],
            'phone' => !is_null($this->guest) ? $this->guest->phone : [],
        ];
    }
}
