<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    public function index()
    {
        $accommodations = Accommodation::all();
        return response()->json($accommodations);
    }
    
    public function getByRoomType($roomTypeId)
    {
        $roomType = \App\Models\RoomType::findOrFail($roomTypeId);
        $validAccommodations = [];
        
        if ($roomType->name === \App\Models\RoomType::ESTANDAR) {
            $validAccommodations = Accommodation::whereIn('name', [Accommodation::SENCILLA, Accommodation::DOBLE])->get();
        } elseif ($roomType->name === \App\Models\RoomType::JUNIOR) {
            $validAccommodations = Accommodation::whereIn('name', [Accommodation::TRIPLE, Accommodation::CUADRUPLE])->get();
        } elseif ($roomType->name === \App\Models\RoomType::SUITE) {
            $validAccommodations = Accommodation::whereIn('name', [Accommodation::SENCILLA, Accommodation::DOBLE, Accommodation::TRIPLE])->get();
        }
        
        return response()->json($validAccommodations);
    }
}