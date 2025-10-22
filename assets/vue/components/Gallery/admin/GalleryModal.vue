<script setup>
import {computed, onMounted, ref} from "vue";
import {getClone} from "../../../helpers/getClone";
import {isValue} from "../../../helpers/isValue";
import GalleryService from "../../../services/GalleryService";
import EditingButtonGroup from "../../Common/EditingButtonGroup.vue";
import GalleryCreateDto from "../../../dto/Gallery/GalleryCreateDto";
import GalleryEditDto from "../../../dto/Gallery/GalleryEditDto";
import EventSelect from "../../Event/admin/EventSelect.vue";

const props = defineProps({
    instance: {
        type: Object,
        required: true,
    },
    gallery: {
        type: Object,
        required: true,
    },
    isEditing: {
        type: Boolean,
        required: false,
        default: false,
    }
});

const gallery = ref(getClone(props.gallery));

let galleryInitData = getClone(gallery.value);

const isEditing = ref(props.isEditing);

const isNewGallery = computed(() => {
    return !isValue(gallery.value.id);
});

const onEditCancel = () => {
    gallery.value = getClone(galleryInitData);
}

const onEditConfirm = () => {
    const galleryData = {
        ...gallery.value,
        event: gallery.value.event?.id
    };

    const promise = isNewGallery.value
        ? GalleryService.newAdmin(new GalleryCreateDto(galleryData))
        : GalleryService.editAdmin(gallery.value.id, new GalleryEditDto(galleryData));

    promise
        .then((response) => {
            gallery.value = response.data.data;

            isEditing.value = false;

            galleryInitData = getClone(gallery.value);
        })
        .catch(err => console.error(err));
}

const dismissModal = () => {
    props.instance.value.close(undefined);
}

const formPs = 2.7;

const iconClass = 'position-absolute top-50 start-0 translate-middle-y ms-3 fs-5';

onMounted(() => {

})

</script>

<template>
    <div class="modal fade" tabindex="-1" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content pb-1">
                <div class="modal-header">
                    <h4 class="modal-title me-3">
                        <template v-if="isNewGallery">Adaugă galerie</template>
                        <template v-else>Detalii galerie</template>
                    </h4>
                    <editing-button-group
                        v-model:is-editing="isEditing"
                        :font-size="5"
                        @confirm="onEditConfirm"
                        @cancel="onEditCancel"
                    ></editing-button-group>
                    <button @click="dismissModal" type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="position-relative mb-4">
                        <input v-model="gallery.title" :disabled="!isEditing" type="text" class="form-control "
                               id="loginEmailInput" placeholder="Titlu" :style="`padding-left: ${formPs}rem`">

                        <i class="bi bi-card-text" :class="iconClass"></i>
                    </div>
                    <event-select v-model:selected-event="gallery.event" :is-disabled="!isEditing">
                    </event-select>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
