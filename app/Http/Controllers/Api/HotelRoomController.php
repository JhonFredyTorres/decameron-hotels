<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelRoom;
use App\Services\HotelRoomValidationService;
use Illuminate\Http\Request;

class HotelRoomController extends Controller
{
    protected $validationService;

    public function __construct(HotelRoomValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    public function index()
    {
        $hotelRooms = HotelRoom::with(['hotel', 'roomType', 'accommodation'])->get();
        return response()->json($hotelRooms);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_id' => 'required|exists:room_types,id',
            'accommodation_id' => 'required|exists:accommodations,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        // Validar que el tipo de habitación y la acomodación sean compatibles
        if (!$this->validationService->validateRoomTypeAndAccommodation(
            $validated['room_type_id'],
            $validated['accommodation_id']
        )) {
            return response()->json([
                'message' => 'El tipo de habitación y la acomodación seleccionados no son compatibles.'
            ], 422);
        }
        
        // Validar que no se exceda el total de habitaciones del hotel
        if (!$this->validationService->validateTotalRoomsNotExceeded(
            $validated['hotel_id'],
            $validated['quantity']
        )) {
            return response()->json([
                'message' => 'La cantidad de habitaciones excede el límite del hotel.'
            ], 422);
        }
        
        // Validar que no exista una combinación duplicada
        $exists = HotelRoom::where('hotel_id', $validated['hotel_id'])
            ->where('room_type_id', $validated['room_type_id'])
            ->where('accommodation_id', $validated['accommodation_id'])
            ->exists();
            
        if ($exists) {
            return response()->json([
                'message' => 'Ya existe una configuración con este tipo de habitación y acomodación para este hotel.'
            ], 422);
        }

        $hotelRoom = HotelRoom::create($validated);

        return response()->json($hotelRoom, 201);
    }

    public function show(HotelRoom $hotelRoom)
    {
        return response()->json($hotelRoom->load(['hotel', 'roomType', 'accommodation']));
    }

    public function update(Request $request, HotelRoom $hotelRoom)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_id' => 'required|exists:room_types,id',
            'accommodation_id' => 'required|exists:accommodations,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        // Validar que el tipo de habitación y la acomodación sean compatibles
        if (!$this->validationService->validateRoomTypeAndAccommodation(
            $validated['room_type_id'],
            $validated['accommodation_id']
        )) {
            return response()->json([
                'message' => 'El tipo de habitación y la acomodación seleccionados no son compatibles.'
            ], 422);
        }
        
        // Validar que no se exceda el total de habitaciones del hotel
        if (!$this->validationService->validateTotalRoomsNotExceeded(
            $validated['hotel_id'],
            $validated['quantity'],
            $hotelRoom->id
        )) {
            return response()->json([
                'message' => 'La cantidad de habitaciones excede el límite del hotel.'
            ], 422);
        }
        
        // Validar que no exista una combinación duplicada
        $exists = HotelRoom::where('hotel_id', $validated['hotel_id'])
            ->where('room_type_id', $validated['room_type_id'])
            ->where('accommodation_id', $validated['accommodation_id'])
            ->where('id', '!=', $hotelRoom->id)
            ->exists();
            
        if ($exists) {
            return response()->json([
                'message' => 'Ya existe una configuración con este tipo de habitación y acomodación para este hotel.'
            ], 422);
        }

        $hotelRoom->update($validated);

        return response()->json($hotelRoom->load(['hotel', 'roomType', 'accommodation']));
    }

    public function destroy(HotelRoom $hotelRoom)
    {
        $hotelRoom->delete();

        return response()->json(null, 204);
    }
}