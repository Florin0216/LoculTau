<script setup>

import {onMounted, ref} from "vue";
import EventService from "../../../services/EventService";
import ModalManager from "../../../services/ModalManager";
import EventModal from "./EventModal.vue";
import EventModel from "../../../models/EventModel";

const events = ref([]);

const getEvents = () => {
    EventService
        .listAdmin()
        .then((response) => {
            events.value = response.data.data;
        })
}

const openEventModal = (event = null, isEditing = false) => {
    ModalManager
        .open({
            component: EventModal,
            props: {
                event: event ?? new EventModel(),
                isEditing: isEditing,
            }
        })
        .then(() => {
            getEvents();
        })
}

const formatDate = (datetime) => {
    const date = new Date(datetime);

    return date.toLocaleDateString('ro-EU', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false // 24-hour format; set to true for AM/PM
    })
}

onMounted(() => {
    getEvents();
})

</script>

<template>
    <div class="card rounded-4 shadow-sm border-gray-200 overflow-hidden">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h3 class="fw-bold mb-0">Evenimente</h3>

                <button @click="openEventModal" class="ms-auto btn btn-primary rounded-4">
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
                        <th>Data</th>
                        <th class="min-width-column">Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="event in events"
                        :key="event.id"
                    >
                        <td>{{event.id}}</td>
                        <td>{{event.title}}</td>
                        <td>{{formatDate(event.date)}}</td>
                        <td>
                            <div class="d-flex-center gap-2">
                                <i @click="openEventModal(event)" class="bi bi-eye cursor-pointer"></i>
                                <i @click="openEventModal(event, true)" class="bi bi-pencil text-primary cursor-pointer"></i>
                                <i class="bi bi-trash text-danger"></i>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>

</style>
