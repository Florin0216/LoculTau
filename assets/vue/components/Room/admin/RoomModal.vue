<script setup>

import EditingButtonGroup from "../../Common/EditingButtonGroup.vue";
import RoomSelect from "./RoomSelect.vue";
import {computed, onMounted, ref} from "vue";
import {getClone} from "../../../helpers/getClone";
import {isValue} from "../../../helpers/isValue";
import EventService from "../../../services/EventService";
import EventCreateDto from "../../../dto/Event/EventCreateDto";
import EventEditDto from "../../../dto/Event/EventEditDto";
import RoomService from "../../../services/RoomService";
import RoomCreateDto from "../../../dto/Room/RoomCreateDto";
import RoomEditDto from "../../../dto/Room/RoomEditDto";

const props = defineProps({
    instance: {
        type: Object,
        required: true,
    },
    room: {
        type: Object,
        required: true,
    },
    isEditing: {
        type: Boolean,
        required: false,
        default: false,
    }
});

const room = ref(getClone(props.room));

let eventInitData = getClone(room.value);

const isEditing = ref(props.isEditing);

const isNewEvent = computed(() => {
    return !isValue(room.value.id);
});

const onEditCancel = () => {
    room.value = getClone(eventInitData);
}

const onEditConfirm = () => {
    const promise = isNewEvent.value
        ? RoomService.newAdmin(new RoomCreateDto(room.value))
        : RoomService.editAdmin(room.value.id, new RoomEditDto(room.value));

    promise
        .then((response) => {
            room.value = response.data.data;

            isEditing.value = false;

            eventInitData = getClone(room.value);
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
                        <template v-if="isNewEvent">Adaugă sală</template>
                        <template v-else>Detalii sală</template>
                    </h4>
                    <editing-button-group
                        v-model:is-editing="isEditing"
                        :font-size="5"
                        @confirm="onEditConfirm"
                        @cancel="onEditCancel"
                    > </editing-button-group>
                    <button @click="dismissModal" type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="position-relative mb-4">
                        <input v-model="room.name" :disabled="!isEditing" type="text" class="form-control " id="loginEmailInput" placeholder="Nume" :style="`padding-left: ${formPs}rem`">

                        <i class="bi bi-card-text" :class="iconClass"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
