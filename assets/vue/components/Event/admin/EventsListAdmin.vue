<script setup>

import {onMounted, ref} from "vue";
import EventService from "../../../services/EventService";
import ModalManager from "../../../services/ModalManager";
import EventModal from "./EventModal.vue";
import EventModel from "../../../models/EventModel";
import EventSeatsListAdmin from "./EventSeatsListAdmin.vue";
import EventReminderModal from "./EventReminderModal.vue";

const selectedEvent = ref(null);

const events = ref([]);

const seatSelectionEnabled = ref(false);

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

const openEventReminderModal = (event = null) => {
    ModalManager
        .open({
            component: EventReminderModal,
            props: {
                event: event ?? new EventModel(),
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
        <template v-if="!selectedEvent">
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
                        <tr v-for="event in events" :key="event.id" @click="selectedEvent = event">
                            <td>{{event.id}}</td>
                            <td>{{event.title}}</td>
                            <td>{{formatDate(event.date)}}</td>
                            <td @click.stop>
                                <div class="d-flex-center gap-2">
                                    <i @click="openEventModal(event)" class="bi bi-eye cursor-pointer"></i>
                                    <i @click="openEventModal(event, true)" class="bi bi-pencil text-primary cursor-pointer"></i>
                                    <i @click="openEventReminderModal(event)" class="bi text-warning bi-bell cursor-pointer"></i>
                                    <i class="bi bi-trash text-danger"></i>
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
                    <button @click="selectedEvent = null;" class="btn btn-light border-gray-200">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <h3 class="mb-0">{{selectedEvent.title}}</h3>
                </div>

                <div class="d-flex-center my-4">
                    <button v-if="!seatSelectionEnabled" @click="seatSelectionEnabled = true" class="btn btn-sm btn-outline-success">
                        Selecteaza locuri
                    </button>
                    <button v-else @click="seatSelectionEnabled = false" class="btn btn-sm btn-outline-danger">
                        Anulează
                    </button>
                </div>

                <div style="min-width: max-content;">
                    <event-seats-list-admin
                        v-model:seat-selection-enabled="seatSelectionEnabled"
                        :event="selectedEvent"
                    > </event-seats-list-admin>
                </div>
            </div>
        </template>
    </div>
</template>

<style scoped>

</style>
