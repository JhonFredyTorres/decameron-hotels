<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run()
    {
        $roomTypes = [
            ['name' => RoomType::ESTANDAR],
            ['name' => RoomType::JUNIOR],
            ['name' => RoomType::SUITE],
        ];

        foreach ($roomTypes as $roomType) {
            RoomType::create($roomType);
        }
    }
}