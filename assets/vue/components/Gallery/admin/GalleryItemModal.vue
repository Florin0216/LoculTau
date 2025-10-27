<script setup>
import {computed, onMounted, ref} from "vue";
import GalleryItemService from "../../../services/GalleryItemService";
import GalleryItemCreateDto from "../../../dto/GalleryItem/GalleryItemCreateDto";
import {getClone} from "../../../helpers/getClone";

const props = defineProps({
    instance: {
        type: Object,
        required: true,
    },
    gallery: {
        type: Object,
        required: true,
    },
});

const title = ref();

const selectedFile = ref(null);

const dismissModal = () => {
    props.instance.value.close(undefined);
}

const handleFileChange = (event) => {
    const file = event.target.files[0];

    const reader = new FileReader();
    reader.onload = (event) => {
        selectedFile.value = {
            base64: event.target.result,
            originalName: file.name
        };
    };
    reader.readAsDataURL(file);
}

const formPs = 2.7;

const iconClass = 'position-absolute top-50 start-0 translate-middle-y ms-3 fs-5';

const onSubmit = () => {
    const galleryItemData = {
        title: title.value,
        gallery: props.gallery.id,
        imageFile: selectedFile.value,
    }

    const promise = GalleryItemService.newAdmin(props.gallery.id, new GalleryItemCreateDto(galleryItemData))

    promise
        .then((response) => {
            dismissModal();
        })
        .catch(err => console.error(err));
}

onMounted(() => {
})
</script>

<template>
    <div class="modal fade" tabindex="-1" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content pb-1">
                <div class="modal-header">
                    <h4 class="modal-title me-3">
                        Adaugă fotografie
                    </h4>
                    <button @click="dismissModal" type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="position-relative">
                        <div class="position-relative mb-4">
                            <input v-model="title" type="text" class="form-control "
                                   id="loginEmailInput" placeholder="Nume" :style="`padding-left: ${formPs}rem`">

                            <i class="bi bi-card-text" :class="iconClass"></i>
                        </div>
                        <div class="position-relative mb-4">
                            <input @change="handleFileChange" type="file" class="form-control"
                                   id="loginEmailInput" :style="`padding-left: ${formPs}rem`"/>

                            <i class="bi bi-paperclip" :class="iconClass"></i>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button @click="onSubmit" type="button" class="btn btn-primary">Salveaza</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
