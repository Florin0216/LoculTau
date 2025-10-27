<script setup>

import {computed, onMounted, ref} from "vue";
import {getClone} from "../../../helpers/getClone";
import {isValue} from "../../../helpers/isValue";
import EditingButtonGroup from "../../Common/EditingButtonGroup.vue";
import RoomSelect from "../../Room/admin/RoomSelect.vue";
import EventService from "../../../services/EventService";
import EventCreateDto from "../../../dto/Event/EventCreateDto";
import EventEditDto from "../../../dto/Event/EventEditDto";
import SponsorSelect from "../../Sponsor/admin/SponsorSelect.vue";

const props = defineProps({
    instance: {
        type: Object,
        required: true,
    },
    event: {
        type: Object,
        required: true,
    },
    isEditing: {
        type: Boolean,
        required: false,
        default: false,
    }
});

const event = ref(getClone(props.event));

let eventInitData = getClone(event.value);

const isEditing = ref(props.isEditing);

const selectedFile = ref(null);

const isNewEvent = computed(() => {
    return !isValue(event.value.id);
});

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

const onEditCancel = () => {
    event.value = getClone(eventInitData);
}


const onEditConfirm = () => {
    const eventData = {
        ...event.value,
        imageFile: selectedFile.value,
        sponsors: event.value.sponsors.map(s => s.id)
    };

    const promise = isNewEvent.value
        ? EventService.newAdmin(new EventCreateDto(eventData))
        : EventService.editAdmin(event.value.id, new EventEditDto(eventData));

    promise
        .then((response) => {
            event.value = response.data.data;

            isEditing.value = false;

            eventInitData = getClone(event.value);
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
                        <template v-if="isNewEvent">Adaugă eveniment</template>
                        <template v-else>Detalii eveniment</template>
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
                        <input v-model="event.title" :disabled="!isEditing" type="text" class="form-control "
                               id="loginEmailInput" placeholder="Nume" :style="`padding-left: ${formPs}rem`">

                        <i class="bi bi-card-text" :class="iconClass"></i>
                    </div>
                    <div class="position-relative mb-4">
                        <input v-model="event.date" :disabled="!isEditing" type="datetime-local" class="form-control "
                               id="loginEmailInput" placeholder="Data" :style="`padding-left: ${formPs}rem`">

                        <i class="bi bi-calendar-event" :class="iconClass"></i>
                    </div>
                    <div class="position-relative mb-4">
                        <input @change="handleFileChange" :disabled="!isEditing" type="file" class="form-control"
                               id="loginEmailInput" :style="`padding-left: ${formPs}rem`"/>

                        <i class="bi bi-paperclip" :class="iconClass"></i>
                    </div>

                    <room-select v-model:selected-room="event.room" :is-disabled="!isEditing"></room-select>
                    <sponsor-select :event="event" v-model:selected-sponsors="event.sponsors"
                                    :is-disabled="!isEditing"></sponsor-select>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
