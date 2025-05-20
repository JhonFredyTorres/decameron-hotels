# Diagrama Entidad-Relación - Sistema de Gestión Hotelera Decameron

## Entidades

### hotels
- **Atributos**:
  - id (PK)
  - name
  - address
  - city
  - nit (UNIQUE)
  - total_rooms
  - created_at
  - updated_at

### room_types
- **Atributos**:
  - id (PK)
  - name
  - created_at
  - updated_at

### accommodations
- **Atributos**:
  - id (PK)
  - name
  - created_at
  - updated_at

### hotel_rooms
- **Atributos**:
  - id (PK)
  - hotel_id (FK)
  - room_type_id (FK)
  - accommodation_id (FK)
  - quantity
  - created_at
  - updated_at

## Relaciones

- Un **hotel** puede tener muchos **hotel_rooms**
- Un **room_type** puede estar en muchos **hotel_rooms**
- Una **accommodation** puede estar en muchos **hotel_rooms**
- Cada **hotel_room** pertenece exactamente a un **hotel**, un **room_type** y una **accommodation**

## Restricciones

1. El NIT del hotel debe ser único
2. La combinación (hotel_id, room_type_id, accommodation_id) debe ser única en hotel_rooms
3. La suma de los valores de quantity en hotel_rooms para un hotel específico no debe exceder su total_rooms
4. Las acomodaciones válidas para cada tipo de habitación están restringidas:
   - Estándar: Sencilla o Doble
   - Junior: Triple o Cuádruple
   - Suite: Sencilla, Doble o Triple