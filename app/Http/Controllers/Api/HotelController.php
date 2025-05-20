<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::all();
        return response()->json($hotels);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'nit' => 'required|string|unique:hotels,nit',
            'total_rooms' => 'required|integer|min:1',
        ]);

        $hotel = Hotel::create($validated);

        return response()->json($hotel, 201);
    }

    public function show(Hotel $hotel)
    {
        return response()->json($hotel);
    }

    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'nit' => [
                'required',
                'string',
                Rule::unique('hotels')->ignore($hotel->id),
            ],
            'total_rooms' => 'required|integer|min:1',
        ]);

        $hotel->update($validated);

        return response()->json($hotel);
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();

        return response()->json(null, 204);
    }
    
    public function getRoomsDetail(Hotel $hotel)
    {
        $hotelRooms = $hotel->hotelRooms()
            ->with('roomType', 'accommodation')
            ->get();
            
        return response()->json($hotelRooms);
    }
}