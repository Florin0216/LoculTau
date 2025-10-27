import FosJsRouting from "../../js/fosJsRouting";
import axios from "axios";

class SeatService{
    getSeats(room, event, args = {}) {
        args.roomId = room.id;
        args.eventId = event.id;

        const requestUrl = FosJsRouting.generate('seating_seat_list', args);

        return axios
            .get(requestUrl)
            .then(response => {
                return response.data
            })
            .catch(err => {
                console.error("Error fetching seats:", err);
            });
    };
}

export default new SeatService();
