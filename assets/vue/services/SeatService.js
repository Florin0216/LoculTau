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

    editAdmin(id,seat, args={}){
        args.id = id;

        let requestUrl = FosJsRouting.generate('admin_seating_seat_edit', args);

        return axios
            .put(requestUrl, {
                data: seat,
            });
    }
}

export default new SeatService();
