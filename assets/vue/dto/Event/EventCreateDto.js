
class EventCreateDto {
    title = null;
    date = null;
    room = null;
    imageFile = null;
    sponsors = null;


    constructor(event) {
        this.title = event.title;
        this.room = event.room.id;
        this.date = event.date;
        this.imageFile = event.imageFile;
        this.sponsors = event.sponsors;
    }
}

export default EventCreateDto;
