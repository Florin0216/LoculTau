import FosJsRouting from "../../js/fosJsRouting";
import axios from "axios";

class ReminderService {

    listReminders(event, args = {}) {
        args.id = event.id;

        let requestUrl = FosJsRouting.generate('admin_seating_reminder_list_reminders', args);
        return axios
            .get(requestUrl)
            .catch(err => console.error(err));

    }

    new(reminderData, args = {}) {
        let requestUrl = FosJsRouting.generate('admin_seating_reminder_new', args);

        return axios
            .post(requestUrl, reminderData)
            .catch(err => console.error(err));
    }

    deleteAdmin(reminder, args = {}) {
        args.id = reminder.id;

        let requestUrl = FosJsRouting.generate('admin_seating_reminder_delete', args);

        return axios
            .delete(requestUrl);
    }
}

export default new ReminderService()
