import FosJsRouting from "../../js/fosJsRouting";
import axios from "axios";

class RoomService {
    listAdmin(args = {}) {
        args._format = 'json';

        let requestUrl = FosJsRouting.generate('admin_seating_room_list', args);

        return axios
            .get(requestUrl)
            .catch(err => console.error(err));
    }

    newAdmin(data, args = {}) {
        let requestUrl = FosJsRouting.generate('admin_seating_room_new', args);

        return axios
            .post(requestUrl, {
                data: data,
            });
    }

    editAdmin(roomId, data, args = {}) {
        args.id = roomId;

        let requestUrl = FosJsRouting.generate('admin_seating_room_edit', args);

        return axios
            .put(requestUrl, {
                data: data,
            });
    }

    deleteAdmin(room, args = {}) {
        args.id = room.id;

        let requestUrl = FosJsRouting.generate('admin_seating_room_delete', args);

        return axios
            .delete(requestUrl);
    }
}

export default new RoomService();
