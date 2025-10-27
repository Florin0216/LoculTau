class GalleryItemCreateDto{
    title = null;
    gallery = null;
    imageFile = null;

    constructor(galleryItem) {
        this.title = galleryItem.title;
        this.gallery = galleryItem.gallery;
        this.imageFile = galleryItem.imageFile;
    }
}

export default GalleryItemCreateDto;
