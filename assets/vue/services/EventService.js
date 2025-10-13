import axios from "axios";
import FosJsRouting from "../../js/fosJsRouting";
import {isValue} from "../helpers/isValue";

class EventService {
    getEvents(args = {}) {
        const requestUrl = FosJsRouting.generate('seating_event_fetch', args);

        return axios
            .get(requestUrl)
            .then(response => {
                return response.data;
            })
            .catch(err => {
                console.error("Error fetching seats:", err);
            });
    }

    listAdmin(args = {}) {
        args._format = 'json';

        const requestUrl = FosJsRouting.generate('admin_seating_event_list', args)

        return axios
            .get(requestUrl)
            .catch(err => console.error(err));
    }

    newAdmin(event, args = {}) {
        const requestUrl = FosJsRouting.generate('admin_seating_event_new', args);

        return axios
            .post(requestUrl, {
                data: event,
            })
    }

    editAdmin(eventId, data, args = {}) {
        args.id = eventId;

        const requestUrl = FosJsRouting.generate('admin_seating_event_edit', args);

        return axios
            .put(requestUrl, {
                data: data,
            })
    }

    listSeatsAdmin(eventId, data, args = {}) {
        const requestUrl = FosJsRouting.generate('')
    }

    #normalize(event, action) {
        const actionFieldsMap = {
            'create': ['title', 'date', 'room'],
            'edit': ['title', 'date', 'room']
        };

        let data = {};
        for (let field of actionFieldsMap[action]) {
            if (isValue(event[field])) {
                data[field] = typeof event[field] === 'object' ? event[field].id : event[field];
            } else {
                data[field] = null;
            }
        }

        return data;
    }
}

export default new EventService();
