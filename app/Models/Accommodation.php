<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function hotelRooms()
    {
        return $this->hasMany(HotelRoom::class);
    }
    
    // Constantes para las acomodaciones
    const SENCILLA = 'Sencilla';
    const DOBLE = 'Doble';
    const TRIPLE = 'Triple';
    const CUADRUPLE = 'Cuádruple';
}