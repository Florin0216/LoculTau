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
        let requestUrl = FosJsRouting.generate('')
    }
}

export default new ReservationService();
