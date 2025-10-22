
class RoomCreateDto {
    name = null;
    capacity = null;

    constructor(room) {
        this.name = room.name;
        this.capacity = room.capacity;
    }
}

export default RoomCreateDto;
