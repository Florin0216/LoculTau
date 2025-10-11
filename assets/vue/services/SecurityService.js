import FosJsRouting from "../../js/fosJsRouting";
import axios from "axios";

class SecurityService {
    getUser = () => {
        let requestUrl = FosJsRouting.generate('public_user_user_show');

        return axios
            .get(requestUrl)
            .catch(err => console.error(err));
    }
}

export default new SecurityService();
