<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function hotelRooms()
    {
        return $this->hasMany(HotelRoom::class);
    }
    
    // Constantes para los tipos de habitación
    const ESTANDAR = 'Estándar';
    const JUNIOR = 'Junior';
    const SUITE = 'Suite';
}