import FosJsRouting from "../../js/fosJsRouting";
import axios from "axios";

class GalleryItemService {
    list(gallery, args = {}) {
        args.id = gallery.id;

        const requestUrl = FosJsRouting.generate('public_app_gallery_item_list', args);

        return axios
            .get(requestUrl)
            .then(response => {
                return response.data
            })
            .catch(err => {
                console.error("Error fetching photos:", err);
            });
    };

    newAdmin(galleryId, data, args = {}) {
        args.id = galleryId;

        const requestUrl = FosJsRouting.generate('admin_app_gallery_item_new', args);

        return axios
            .post(requestUrl, {
                data: data
            })
    };

    deleteAdmin(galleryId, itemId, data, args = {}) {
        args.id = galleryId;
        args.itemId = itemId;

        const requestUrl = FosJsRouting.generate('admin_app_gallery_item_delete', args);

        return axios
            .delete(requestUrl, {
                data: data
            })
    };
}

export default new GalleryItemService();
