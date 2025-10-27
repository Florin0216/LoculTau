import FosJsRouting from "../../js/fosJsRouting";
import axios from "axios";

class GalleryService{
    list(eventId, args = {}) {
        args._format = 'json';
        args.eventId = eventId;

        const requestUrl = FosJsRouting.generate('public_app_gallery_list', args)

        return axios
            .get(requestUrl)
            .catch(err => console.error(err));
    }

    listAdmin(args = {}) {
        args._format = 'json';

        const requestUrl = FosJsRouting.generate('admin_app_gallery_list', args)

        return axios
            .get(requestUrl)
            .catch(err => console.error(err));
    }

    newAdmin(gallery, args = {}) {
        const requestUrl = FosJsRouting.generate('admin_app_gallery_new', args);

        return axios
            .post(requestUrl, {
                data: gallery,
            })
    }

    editAdmin(galleryId, gallery, args = {}) {
        args.id = galleryId;

        const requestUrl = FosJsRouting.generate('admin_app_gallery_edit', args);

        return axios
            .put(requestUrl, {
                data: gallery,
            })
    }

    deleteAdmin(gallery, args = {}) {
        args.id = gallery.id;

        let requestUrl = FosJsRouting.generate('admin_app_gallery_delete', args);

        return axios
            .delete(requestUrl);
    }
}
export  default new GalleryService();
