<?php

namespace App\Http\Controllers;

use App\Http\Resources\Guest\ListResource;
use App\Http\Resources\Guest\ShowResource;
use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $query = new Guest();
        if(isset($request['phone']) && $request['phone'] != "") {
            $query = $query->where('phone', $request['phone'])
                ->orWhere('phone', 'like', '%' . $request['phone'] . '%');
        }

        if(isset($request['name']) && $request['name'] != "") {
            $query = $query->where('fname', $request['name'])
                ->orWhere('fname', 'like', '%' . $request['name'] . '%')
                ->orWhere('lname', $request['name'])
                ->orWhere('lname', 'like', '%' . $request['name'] . '%');
        }

        $array = resource_index(ListResource::collection($query->paginate($request['perPage'] ?? env('APP_PAGE_NUM'))));
        return json_true($array['data'], $array['meta']);
    }

    public function store(Request $request)
    {
        $validate = $this->validateForm($request, [
            'fname' => 'required|string|max:80',
            'lname' => 'required|string|max:80',
            'phone' => 'required|string',
        ]);

        if (count($validate) != 0) {
            return json_false($validate, 422);
        } else {
            $guest = Guest::create([
                'fname' => $request['fname'],
                'lname' => $request['lname'],
                'phone' => $request['phone'],
            ]);

            return json_true([
                'message' => __('item-created')
            ]);
        }
    }

    public function show(Request $request)
    {
        $guest = Guest::findOrFail($request['id']);

        return json_true(ShowResource::make($guest));
    }

    public function update(Request $request)
    {
        $guest = Guest::findOrFail($request['id']);

        $validate = $this->validateForm($request, [
            'fname' => 'required|string|max:80',
            'lname' => 'required|string|max:80',
            'phone' => 'required|string',
        ]);

        if (count($validate) != 0) {
            return json_false($validate, 422);
        } else {
            $guest->update([
                'fname' => $request['fname'],
                'lname' => $request['lname'],
                'phone' => $request['phone'],
            ]);

            return json_true([
                __('item-update')
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $guest = Guest::findOrFail($request['id']);

        if($guest->delete()) {
            return json_true([
                __('item-destroy')
            ]);
        }
    }
}
