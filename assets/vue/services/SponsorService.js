import FosJsRouting from "../../js/fosJsRouting";
import axios from "axios";

class SponsorService {

    listAdmin(args = {}) {
        args._format = 'json';

        const requestUrl = FosJsRouting.generate('admin_seating_sponsor_list', args)

        return axios
            .get(requestUrl)
            .catch(err => console.error(err));
    }

    newAdmin(data, args = {}) {
        let requestUrl = FosJsRouting.generate('admin_seating_sponsor_new', args);

        return axios
            .post(requestUrl, {
                data: data,
            });
    }

    editAdmin(sponsorId, data, args = {}) {
        args.id = sponsorId;

        let requestUrl = FosJsRouting.generate('admin_seating_sponsor_edit', args);

        return axios
            .put(requestUrl, {
                data: data,
            });
    }

    deleteAdmin(sponsor, args = {}) {
        args.id = sponsor.id;

        let requestUrl = FosJsRouting.generate('admin_seating_sponsor_delete', args);

        return axios
            .delete(requestUrl);
    }

}

export default new SponsorService()
