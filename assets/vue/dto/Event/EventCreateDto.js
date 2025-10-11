
class EventCreateDto {
    title = null;
    date = null;
    room = null;

    constructor(event) {
        this.title = event.title;
        this.room = event.room.id;
        this.date = event.date;
    }
}

export default EventCreateDto;
