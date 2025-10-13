
class EventCreateDto {
    title = null;
    date = null;
    room = null;
    imageFile = null;


    constructor(event) {
        this.title = event.title;
        this.room = event.room.id;
        this.date = event.date;
        this.imageFile = event.imageFile;
    }
}

export default EventCreateDto;
