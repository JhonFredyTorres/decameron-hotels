<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('hotel_rooms', function (Blueprint $table) {
        $table->id();
        $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
        $table->foreignId('room_type_id')->constrained('room_types');
        $table->foreignId('accommodation_id')->constrained('accommodations');
        $table->integer('quantity');
        $table->timestamps();
        
        // Garantiza que no haya duplicados de tipo y acomodación para un hotel
        $table->unique(['hotel_id', 'room_type_id', 'accommodation_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_rooms_tabl');
    }
};
