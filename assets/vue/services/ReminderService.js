import FosJsRouting from "../../js/fosJsRouting";
import axios from "axios";

class ReminderService{

    new(reminderData, args = {}) {
        let requestUrl = FosJsRouting.generate('admin_seating_reminder_new', args);

        return axios
            .post(requestUrl, reminderData)
            .catch(err => console.error(err));
    }
}

export default new ReminderService()
