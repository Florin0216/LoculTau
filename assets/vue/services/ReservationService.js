import FosJsRouting from "../../js/fosJsRouting";
import axios from "axios";

class ReservationService {
    new(reservationData, args = {}) {
        let requestUrl = FosJsRouting.generate('seating_reservation_new', args);

        return axios
            .post(requestUrl, reservationData)
            .catch(err => console.error(err));
    }

    listAdmin(args = {}) {
        args._format = 'json';

        let requestUrl = FosJsRouting.generate('admin_seating_reservation_list', args);

        return axios
            .get(requestUrl)
            .catch(err => console.error(err));
    }

    showAdmin(reservationId = '-', seatId = '-', eventId = '-', args = {}) {
        args._format = 'json';
        args.id = reservationId;
        args.seatId = seatId;
        args.eventId = eventId;

        let requestUrl = FosJsRouting.generate('admin_seating_reservation_show', args);

        return axios
            .get(requestUrl)
            .catch(err => console.error(err));
    }
}

export default new ReservationService();
