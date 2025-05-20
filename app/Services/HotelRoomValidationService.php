<?php

namespace App\Services;

use App\Models\Accommodation;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\RoomType;

class HotelRoomValidationService
{
    /**
     * Valida que el tipo de habitación y la acomodación sean compatibles
     */
    public function validateRoomTypeAndAccommodation(int $roomTypeId, int $accommodationId): bool
    {
        $roomType = RoomType::findOrFail($roomTypeId);
        $accommodation = Accommodation::findOrFail($accommodationId);

        // Si el tipo de habitación es Estándar: la acomodación debe ser Sencilla o Doble
        if ($roomType->name === RoomType::ESTANDAR) {
            return in_array($accommodation->name, [Accommodation::SENCILLA, Accommodation::DOBLE]);
        }

        // Si el tipo de habitación es Junior: la acomodación debe ser Triple o Cuádruple
        if ($roomType->name === RoomType::JUNIOR) {
            return in_array($accommodation->name, [Accommodation::TRIPLE, Accommodation::CUADRUPLE]);
        }

        // Si el tipo de habitación es Suite: la acomodación debe ser Sencilla, Doble o Triple
        if ($roomType->name === RoomType::SUITE) {
            return in_array($accommodation->name, [Accommodation::SENCILLA, Accommodation::DOBLE, Accommodation::TRIPLE]);
        }

        return false;
    }

    /**
     * Valida que la cantidad total de habitaciones no exceda el máximo definido para el hotel
     */
    public function validateTotalRoomsNotExceeded(int $hotelId, int $newQuantity, ?int $currentHotelRoomId = null): bool
    {
        $hotel = Hotel::findOrFail($hotelId);
        
        $query = HotelRoom::where('hotel_id', $hotelId);
        
        // Si estamos actualizando, excluimos el registro actual
        if ($currentHotelRoomId) {
            $query->where('id', '!=', $currentHotelRoomId);
        }
        
        $currentTotal = $query->sum('quantity');
        
        return ($currentTotal + $newQuantity) <= $hotel->total_rooms;
    }
}