<?php

namespace App\Http\Controllers;

use App\Http\Resources\Room\ListResource;
use App\Http\Resources\Room\ShowResource;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = new Room();
        if(isset($request['code']) && $request['code'] != "") {
            $query = $query->where('code', $request['code'])
                ->orWhere('code', 'like', '%' . $request['code'] . '%');
        }

        $array = resource_index(ListResource::collection($query->paginate($request['perPage'] ?? env('APP_PAGE_NUM'))));
        return json_true($array['data'], $array['meta']);
    }

    public function store(Request $request)
    {
        $validate = $this->validateForm($request, [
            'code' => 'required|string|max:6',
        ]);

        if (count($validate) != 0) {
            return json_false($validate, 422);
        } else {
            $subject = Room::create([
                'code' => $request['code'],
            ]);

            return json_true([
                'message' => __('item-created')
            ]);
        }
    }

    public function show(Request $request)
    {
        $room = Room::findOrFail($request['id']);

        return json_true(ShowResource::make($room));
    }

    public function update(Request $request)
    {
        $room = Room::findOrFail($request['id']);

        $validate = $this->validateForm($request, [
            'status' => 'required|string',
        ]);

        if (count($validate) != 0) {
            return json_false($validate, 422);
        } else {
            $room->update([
                'status' => $request['status'],
            ]);

            return json_true([
                __('item-update')
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $room = Room::findOrFail($request['id']);

        if($room->delete()) {
            return json_true([
                __('item-destroy')
            ]);
        }
    }
}
