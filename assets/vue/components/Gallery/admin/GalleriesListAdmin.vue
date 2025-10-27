<script setup>
import {onMounted, ref} from "vue";
import GalleryService from "../../../services/GalleryService";
import ModalManager from "../../../services/ModalManager";
import GalleryModel from "../../../models/GalleryModel";
import GalleryModal from "./GalleryModal.vue";
import GalleryItemsListAdmin from "./GalleryItemsListAdmin.vue";
import EventService from "../../../services/EventService";

const galleries = ref([]);

const selectedGallery = ref();

const getGalleries = () => {
    GalleryService
        .listAdmin()
        .then((response) => {
            galleries.value = response.data.data;
        })
}

const openGalleryModal = (gallery = null, isEditing = false) => {
    ModalManager
        .open({
            component: GalleryModal,
            props: {
                gallery: gallery ?? new GalleryModel(),
                isEditing: isEditing,
            }
        })
        .then(() => {
            getGalleries();
        })
}

const onDelete = (gallery) => {
    GalleryService
        .deleteAdmin(gallery)
        .then(() => {
            const index = galleries.value.findIndex(g => g.id === gallery.id);
            if (index !== -1) {
                galleries.value.splice(index, 1);
            }
        })
}

onMounted(() => {
    getGalleries();
})
</script>

<template>
    <div class="card rounded-4 overflow-hidden shadow-sm border-gray-200">
        <template v-if="!selectedGallery">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h3 class="fw-bold mb-0">Galerii</h3>
                    <button @click="openGalleryModal(null, true)" class="ms-auto btn btn-primary rounded-4">
                        <i class="bi bi-plus-lg"></i>
                        Adaugă
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                    <tr>

                        <th class="min-width-column">Id</th>
                        <th>Nume</th>
                        <th class="min-width-column">Acțiuni</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="gallery in galleries" :key="gallery.id" @click="selectedGallery = gallery">
                        <td>{{gallery.id}}</td>
                        <td>{{gallery.title}}</td>
                        <td @click.stop v-if="!gallery.isGeneral">
                            <div class="d-flex-center gap-2">
                                <i @click="openGalleryModal(gallery)" class="bi bi-eye"></i>
                                <i @click="openGalleryModal(gallery, true)" class="bi bi-pencil text-primary"></i>
                                <i @click="onDelete(gallery)" class="bi bi-trash text-danger"></i>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </template>
        <template v-else>
            <div class="card-body overflow-auto">
                <div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-2">
                    <button @click="selectedGallery = null;" class="btn btn-light border-gray-200">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <h3 class="mb-0">{{selectedGallery.title}}</h3>
                </div>
                <div style="min-width: max-content;">
                    <gallery-items-list-admin
                        :gallery="selectedGallery">
                    </gallery-items-list-admin>
                </div>
            </div>
        </template>
    </div>
</template>

<style scoped>

</style>
