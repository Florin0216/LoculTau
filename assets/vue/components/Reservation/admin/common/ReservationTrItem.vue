<script setup>

import ModalManager from "../../../../services/ModalManager";
import DeleteConfirmModal from "../../../Common/DeleteConfirmModal.vue";
import ReservationService from "../../../../services/ReservationService";
import ReservationModal from "../ReservationModal.vue";

const props = defineProps({
    reservation: {
        type: Object,
        required: true,
    },
    collapseUuid: {
        type: Boolean,
        required: false,
        default: false,
    }
})

const emits = defineEmits(['edit', 'delete'])

const formatDate = (datestring) => {
    const date = new Date(datestring);
    return `${date.toLocaleTimeString('ro-RO', {
        timeStyle: 'short'
    })}, ${date.toLocaleDateString('ro-RO', {
        dateStyle: 'short'
    })}`;
}

const initDelete = () => {
    ModalManager
        .open({
            component: DeleteConfirmModal,

        })
        .then((deleteConfirmed) => {
            if (deleteConfirmed) {
                ReservationService
                    .deleteAdmin(props.reservation)
                    .then((response) => {
                        emits('delete');
                    })
            }
        })
}

const openModal = (isEditing = false) => {
    ModalManager
        .open({
            component: ReservationModal,
            props: {
                reservation: props.reservation,
                isEditing: isEditing,
            }
        })
        .then((data) => {

        })
}

</script>

<template>
    <tr class="align-middle">
        <td>{{reservation.id}}</td>
        <td :style="collapseUuid ? { maxWidth: '1rem' } : {}">
            <div class="d-inline-block text-truncate" style="max-width: 100%">
                {{reservation.uuid}}
            </div>
        </td>
        <td>{{formatDate(reservation.updatedAt)}}</td>
        <td>{{reservation.email}}</td>
        <td>{{reservation.name}}</td>
        <td>{{reservation.event.title}}</td>
        <td>
            <div class="d-flex align-items-center gap-1">
                <span>Rând:</span>
                <span class="fw-bold">{{reservation.seat.rowNo}}</span>
            </div>
            <div class="d-flex align-items-center gap-1">
                <span>Loc:</span>
                <span class="fw-bold">{{reservation.seat.number}}</span>
            </div>
            <div class="d-flex align-items-center gap-1 flex-wrap">
                <span>Secțiune:</span>
                <span class="fw-bold">{{reservation.seat.section}}</span>
            </div>
        </td>
        <td>
            <template v-if="reservation.claimedAt" >
                <div class="text-success mb-2 fs-5">Revendicat</div>
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-light">Validat de:</span>
                    <span>{{reservation.claimedBy.username}}</span>
                </div>
            </template>
            <div v-else class="text-muted">In asteptare</div>
        </td>
        <td>
            <div class="d-flex-center gap-2">
                <i @click="openModal(false)" class="bi bi-eye cursor-pointer"></i>
                <i @click="openModal(true)" class="bi bi-pencil cursor-pointer text-primary"></i>
                <i @click="initDelete" class="bi bi-trash cursor-pointer text-danger"></i>
            </div>
        </td>
    </tr>
</template>

<style scoped>

</style>
