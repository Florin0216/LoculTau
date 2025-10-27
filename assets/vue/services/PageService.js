import FosJsRouting from "../../js/fosJsRouting";
import axios from "axios";

class PageService{
    listAdmin(args = {}) {
        args._format = 'json';

        let requestUrl = FosJsRouting.generate('admin_app_page_list', args);

        return axios
            .get(requestUrl)
            .catch(err => console.error(err));
    }

    newAdmin(page, args = {}) {
        let requestUrl = FosJsRouting.generate('admin_app_page_new', args);

        return axios
            .post(requestUrl, {
                data: page,
            });
    }

    editAdmin(pageId, data, args = {}) {
        args.id = pageId;

        let requestUrl = FosJsRouting.generate('admin_app_page_edit', args);

        return axios
            .put(requestUrl, {
                data: data,
            });
    }
}

export default new PageService();
