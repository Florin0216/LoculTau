<script setup>

import RoomSelect from "../../Room/admin/RoomSelect.vue";
import EditingButtonGroup from "../../Common/EditingButtonGroup.vue";
import {computed, ref} from "vue";
import {getClone} from "../../../helpers/getClone";

const props = defineProps({
    instance: {
        type: Object,
        required: true,
    },
    reservation: {
        type: Object,
        required: true,
    },
    isEditing: {
        type: Boolean,
        required: false,
        default: false,
    }
});

const isEditing = ref(props.isEditing);

const reservation = ref(getClone(props.reservation));

let reservationInitData = getClone(reservation.value);

const formattedUpdatedAt = computed(() => {
    const date = new Date(props.reservation.updatedAt);

    const tzOffset = date.getTimezoneOffset() * 60000; // in ms

    return new Date(date - tzOffset).toISOString().slice(0, 16);
});

const onEditCancel = () => {
    isEditing.value = false;

    reservation.value = reservationInitData;
}

const dismissModal = () => {
    props.instance.value.close(undefined);
}

</script>

<template>
    <div class="modal fade" tabindex="-1" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content pb-1">
                <div class="modal-header">
                    <h4 class="modal-title me-3">Detalii rezervare</h4>

                    <editing-button-group
                        v-model:is-editing="isEditing"
                        :font-size="5"
                        @confirm="onEditConfirm"
                        @cancel="onEditCancel"
                    ></editing-button-group>
                    <button @click="dismissModal" type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Email address</label>
                        <input v-model="reservation.email" :disabled="!isEditing" type="email" class="form-control" id="exampleFormControlInput1">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Nume</label>
                        <input v-model="reservation.name" :disabled="!isEditing" type="text" class="form-control" id="exampleFormControlInput1">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Ultima actualizare</label>
                        <input :value="formattedUpdatedAt" disabled type="datetime-local" class="form-control" id="exampleFormControlInput1">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Eveniment</label>
                        <input disabled type="text" class="form-control" id="exampleFormControlInput1" :value="reservation.event.title">
                    </div>
                    <div class="row mb-3">
                        <div class="col-auto">
                            Scaun
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center gap-2">
                                <span>Rând:</span>
                                <span class="fw-bold">{{reservation.seat.rowNo}}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span>Loc:</span>
                                <span class="fw-bold">{{reservation.seat.number}}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span>Secțiune:</span>
                                <span class="fw-bold">{{reservation.seat.section}}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
