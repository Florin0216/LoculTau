class SeatCreateDto{
    rowNo = null;
    number = null;
    section = null;
    room= null;
    sponsor = null;

    constructor(seat) {
        this.rowNo = seat.rowNo;
        this.number = seat.number;
        this.section = seat.section;
        this.room = seat.room;
        this.sponsor = seat.sponsor;
    }
}

export default SeatCreateDto;
