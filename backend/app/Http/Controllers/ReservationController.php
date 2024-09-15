<?php

namespace App\Http\Controllers;

use App\Http\Resources\Reservation\ListResource;
use App\Http\Resources\Guest\ListResource as GuestResource;
use App\Http\Resources\Room\ListResource as RoomResource;
use App\Http\Resources\Reservation\ShowResource;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = new Reservation();
        if(isset($request['code']) && $request['code'] != "") {
            $rooms = Room::where('code', $request['code'])
                ->orWhere('code', 'like', '%' . $request['code'] . '%')->select('id')->get()->toArray();

            $query = $query->whereIn('room_id',array_column($rooms,'id'));
        }

        if(isset($request['name']) && $request['name'] != "") {
            $guests = Guest::where('fname', $request['name'])
                ->orWhere('fname', 'like', '%' . $request['name'] . '%')
                ->orWhere('lname', $request['name'])
                ->orWhere('lname', 'like', '%' . $request['name'] . '%')->select('id')->get()->toArray();

            $query = $query->whereIn('guest_id',array_column($guests,'id'));
        }

        $array = resource_index(ListResource::collection($query->paginate($request['perPage'] ?? env('APP_PAGE_NUM'))));
        return json_true($array['data'], $array['meta']);
    }

    public function guest(Request $request)
    {
        return json_true(GuestResource::collection(Guest::all()));
    }

    public function room(Request $request)
    {
        return json_true(RoomResource::collection(Room::where('status','ready')->get()));
    }

    public function store(Request $request)
    {
        $validate = $this->validateForm($request, [
            'room_id' => 'required',
            'fname' => 'required|string|max:80',
            'lname' => 'required|string|max:80',
            'phone' => 'required|string',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if (count($validate) != 0) {
            return json_false($validate, 422);
        } else {
            $guest = Guest::where('phone',$request['phone'])->first();
            if(is_null($guest)) {
                $guest = Guest::create([
                    'fname' => $request['fname'],
                    'lname' => $request['lname'],
                    'phone' => $request['phone'],
                ]);
            }

            $reservation = Reservation::create([
                'room_id' => $request['room_id'],
                'guest_id' => $guest->id,
                'start_date' => $request['start_date'],
                'end_date' => $request['end_date'],
            ]);

            $room = Room::find($request['room_id']);

            $room->status = 'reserved';
            $room->save();

            return json_true([
                'message' => __('item-created')
            ]);
        }
    }

    public function show(Request $request)
    {
        $reservation = Reservation::findOrFail($request['id']);

        return json_true(ShowResource::make($reservation));
    }

    public function update(Request $request)
    {
        $reservation = Reservation::findOrFail($request['id']);

        $validate = $this->validateForm($request, [
            'room_id' => 'required',
            'start_date' => 'required',
            'fname' => 'required|string|max:80',
            'lname' => 'required|string|max:80',
            'end_date' => 'required',
        ]);

        if (count($validate) != 0) {
            return json_false($validate, 422);
        } else {
            $guest = Guest::where('phone',$request['phone'])->first();

            $guest->update([
                'fname' => $request['fname'],
                'lname' => $request['lname'],
            ]);


            if($reservation->room_id != $request['room_id']) {
                $room = Room::find($reservation->room_id);

                $room->status = 'ready';
                $room->save();
            }

            $reservation->update([
                'room_id' => $request['room_id'],
                'guest_id' => $guest->id,
                'start_date' => $request['start_date'],
                'end_date' => $request['end_date'],
            ]);

            $room = Room::find($request['room_id']);

            $room->status = 'reserved';
            $room->save();

            return json_true([
                __('item-update')
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $reservation = Reservation::findOrFail($request['id']);

        $room = Room::find($reservation->room_id);

        $room->status = 'ready';
        $room->save();

        if($reservation->delete()) {
            return json_true([
                __('item-destroy')
            ]);
        }
    }
}
