
class GalleryCreateDto {
    title = null;
    event = null;

    constructor(gallery) {
        this.title = gallery.title;
        this.event = gallery.event;
    }
}

export default GalleryCreateDto;
