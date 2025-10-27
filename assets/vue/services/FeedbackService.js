import FosJsRouting from "../../js/fosJsRouting";
import axios from "axios";

class FeedbackService {
    listAdmin(args = {}){
        args._format = 'json';

        let requestUrl = FosJsRouting.generate('admin_app_feedback_list', args);

        return axios
            .get(requestUrl)
            .catch(err => console.error(err));
    }

    new(feedback, args = {}) {
        const requestUrl = FosJsRouting.generate('public_app_feedback_new', args);

        return axios.post(requestUrl, {
            data: feedback,
        })
    }
}

export default new FeedbackService()
