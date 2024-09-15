<?php

namespace App\Http\Resources\Reservation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Room\ListResource as RoomResource;
use App\Http\Resources\Guest\ListResource as GuestResource;
use Illuminate\Support\Carbon;

class ListResource extends JsonResource
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
            'start_date' => Carbon::parse($this->start_date)->diffForHumans(),
            'end_date' => Carbon::parse($this->end_date)->diffForHumans(),
            'room' => !is_null($this->room) ? RoomResource::make($this->room) : [],
            'guest' => !is_null($this->guest) ? GuestResource::make($this->guest) : [],
        ];
    }
}
