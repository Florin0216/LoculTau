import FosJsRouting from "../../js/fosJsRouting";
import axios from "axios";

class UserService {
    listAdmin(args = {}) {
        args._format = 'json';

        let requestUrl = FosJsRouting.generate('admin_user_user_list', args);

        return axios
            .get(requestUrl)
            .catch(err => console.error(err));
    }

    newSuperAdmin(data, args = {}) {
        let requestUrl = FosJsRouting.generate('super_admin_user_user_new', args);

        return axios
            .post(requestUrl, {
                data: data,
            });
    }

    editSuperAdmin(userId, data, args = {}) {
        args.id = userId;

        let requestUrl = FosJsRouting.generate('super_admin_user_user_edit', args);

        return axios
            .patch(requestUrl, {
                data: data
            })
    }
}

export default new UserService();
