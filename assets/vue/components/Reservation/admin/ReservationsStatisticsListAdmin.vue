<script setup>

import {computed, onMounted, ref} from "vue";
import ReservationService from "../../../services/ReservationService";

const props = defineProps( {
    events:{
        type: Array,
        required: true
    }
})

const reservations = ref([]);

const selectedEvent = ref(null);

const sponsorRes = computed(() =>
    reservations.value.filter(r => r.sponsor?.id).length
);

const sponsorResParticipation = computed(() =>
    reservations.value.filter(r => r.sponsor?.id && r.claimedAt).length
);

const nonSponsorRes = computed(() =>
    reservations.value.filter(r => !r.sponsor?.id).length
);

const nonSponsorResParticipation = computed(() =>
    reservations.value.filter(r => !r.sponsor?.id && r.claimedAt).length
);

const occupancyRate = computed(() => {
    if (!selectedEvent.value) return 0;
    const event = props.events.find(e => e.id === selectedEvent.value);
    const capacity = event?.room?.capacity || 0;
    if (capacity === 0) return 0;
    return Math.round((reservations.value.length / capacity) * 100);
});

const seatsLeft = computed(() => {
    if (!selectedEvent.value) return 0;
    const event = props.events.find(e => e.id === selectedEvent.value);
    const capacity = event?.room?.capacity || 0;
    return Math.max(0, capacity - reservations.value.length);
});

const roomCapacity = computed(() => {
    if (!selectedEvent.value) return 0;
    const event = props.events.find(e => e.id === selectedEvent.value);
    return event?.room?.capacity || 0;
});


const getReservations = () => {
    if (!selectedEvent.value) return;

    ReservationService
        .listReservationsForEventAdmin(selectedEvent.value)
        .then((response) => {
            reservations.value = response.data.data
        });
};

onMounted(() => {
    getReservations();
});

</script>

<template>
    <div class="container mt-5">
        <h2 class="mb-4">Statisticile rezervarilor</h2>
        <div class="mb-4 col-md-3">
            <label for="eventSelect" class="form-label fs-5">Selectează evenimentul</label>
            <select v-model="selectedEvent" @change="getReservations" id="eventSelect" class="form-select">
                <option disabled selected value="">Selectează evenimentul</option>
                <option v-for="event in events" :key="event.id" :value="event.id">
                    {{ event.title }}
                </option>
            </select>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h3 class="card-title">Rezervari facute cu link de sponsor</h3>
                        <p class="card-text fs-2">{{ sponsorRes }}</p>
                        <h5 class="card-title">Dintre care au participat</h5>
                        <p class="card-text fs-2">{{ sponsorResParticipation }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-body">
                        <h3 class="card-title">Rezervari facute fara link de sponsor</h3>
                        <p class="card-text fs-2">{{ nonSponsorRes }}</p>
                        <h5 class="card-title">Dintre care au participat</h5>
                        <p class="card-text fs-2">{{ nonSponsorResParticipation }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h3 class="card-title">Rata de ocupare</h3>
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar" :style="{ width: occupancyRate + '%' }">
                                {{ occupancyRate }}%
                            </div>
                        </div>
                        <small class="mt-2 d-block">{{ reservations.length }} / {{ roomCapacity }} locuri</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h3 class="card-title">Locuri disponibile</h3>
                        <p class="card-text fs-2">{{ seatsLeft }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
