<script setup>
import {onMounted, ref} from "vue";
import {getClone} from "../../../helpers/getClone";
import PageService from "../../../services/PageService";
import EditingButtonGroup from "../../Common/EditingButtonGroup.vue";
import PageEditDto from "../../../dto/Page/PageEditDto";
import Editor from '@tinymce/tinymce-vue';

const props = defineProps({
    instance: {
        type: Object,
        required: true,
    },
    page: {
        type: Object,
        required: true,
    },
    isEditing: {
        type: Boolean,
        required: false,
        default: false,
    }
});

const editorConfig = {
    height: 400,
    menubar: true,
    plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace code fullscreen',
        'insertdatetime media table paste help wordcount',
        'codesample importcss'
    ],
    toolbar: 'undo redo | styleselect | bold italic underline strikethrough | forecolor backcolor | \
    alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | blockquote hr removeformat',
    style_formats: [
        {title: 'Heading 1', block: 'h1', classes: 'text-4xl md:text-5xl font-bold mb-6'},
        {title: 'Heading 2', block: 'h2', classes: 'text-3xl md:text-4xl font-semibold text-gray-900 mb-5'},
        {title: 'Heading 3', block: 'h3', classes: 'text-2xl md:text-3xl font-medium text-gray-800 mb-4'},
        {title: 'Heading 4', block: 'h4', classes: 'text-xl md:text-2xl font-medium text-gray-700 mb-3'},
        {title: 'Heading 5', block: 'h5', classes: 'text-lg md:text-xl font-semibold text-gray-800 mb-2'},
        {title: 'Heading 6', block: 'h6', classes: 'text-base md:text-lg font-semibold text-gray-700 mb-2'},
        {title: 'Paragraph', block: 'p', classes: 'text-xl text-gray-600 leading-relaxed'},
        {title: 'List', block: 'ul', classes: 'list-disc ml-6 mb-2'}
    ],
};

const page = ref(getClone(props.page));

let eventInitData = getClone(page.value);

const isEditing = ref(props.isEditing);

const onEditCancel = () => {
    page.value = getClone(eventInitData);
}

const onEditConfirm = () => {
    const promise = PageService.editAdmin(page.value.id, new PageEditDto(page.value));

    promise
        .then((response) => {
            page.value = response.data.data;

            isEditing.value = false;

            eventInitData = getClone(page.value);
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
                        Detalii continut pagina
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
                        <input v-model="page.title" disabled type="text" class="form-control "
                               id="loginEmailInput" placeholder="Titlu" :style="`padding-left: ${formPs}rem`">

                        <i class="bi bi-card-text" :class="iconClass"></i>
                    </div>
                    <div class="position-relative mb-4">
                        <input v-model="page.slug" disabled type="text" class="form-control "
                               id="loginEmailInput" placeholder="Sectiune" :style="`padding-left: ${formPs}rem`">

                        <i class="bi bi-people-fill" :class="iconClass"></i>
                    </div>
                    <div class="position-relative mb-4">
                        <input v-model="page.section" disabled type="text" class="form-control "
                               id="loginEmailInput" placeholder="Sectiune" :style="`padding-left: ${formPs}rem`">

                        <i class="bi bi-people-fill" :class="iconClass"></i>
                    </div>
                    <div class="position-relative mb-4">
                        <label class="form-label">Conținut Pagina</label>
                        <Editor
                            api-key="ui9qwdpl07ounjc6hiek90vq04aomnhmkq93wb5fjwb6lxgo"
                            v-model="page.content"
                            :init="editorConfig"
                            :disabled="!isEditing"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
