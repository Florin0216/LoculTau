<script setup>
import {onMounted, ref} from "vue";
import GalleryItemService from "../../../services/GalleryItemService";
import ModalManager from "../../../services/ModalManager";
import GalleryItemModal from "./GalleryItemModal.vue";

const props = defineProps({
    gallery: {
        type: Object,
        required: true,
    }
});

const galleryPhotos = ref([]);

const getGalleryItems = () => {
    GalleryItemService
        .list(props.gallery)
        .then((response) => {
            galleryPhotos.value = response;
        })
}

const openGalleryItemModal = () => {
    ModalManager
        .open({
            component: GalleryItemModal,
            props: {
                gallery: props.gallery
            }
        })
        .then(() => {
            getGalleryItems();
        })

}

const deletePhoto = (index) => {
    const photo = galleryPhotos.value[index];

    if (confirm("Sigur vrei să ștergi această poză?")) {
        GalleryItemService
            .deleteAdmin(props.gallery.id, photo.id)
            .then(() => {
                galleryPhotos.value.splice(index, 1);
            })
            .catch((error) => {
                console.error("Failed to delete photo:", error);
            });
    }
}

onMounted(() =>{
    getGalleryItems();
});
</script>

<template>
    <div class="gallery-items-admin">
        <div class="d-flex justify-content-end mb-3">
            <button @click="openGalleryItemModal" class="btn btn-primary rounded-pill">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>

        <div class="row g-2 g-md-3">
            <div
                class="col-6 col-sm-4 col-md-3 col-lg-2"
                v-for="(photo, index) in galleryPhotos"
                :key="photo.id"
            >
                <div class="card border-0 shadow-sm position-relative overflow-hidden rounded-4">
                    <div class="ratio ratio-1x1">
                        <img
                            :src="photo.thumbnail.url"
                            alt="Gallery Photo"
                        />
                    </div>

                    <button
                        @click="deletePhoto(index)"
                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle p-0 d-flex align-items-center justify-content-center"
                        style="width: 32px; height: 32px;"
                        title="Șterge"
                    >
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="galleryPhotos.length === 0" class="text-center text-muted py-5">
            <i class="bi bi-images fs-1 d-block mb-3"></i>
            <p>Nu există fotografii în această galerie</p>
        </div>
    </div>
</template>


<style scoped>

</style>
