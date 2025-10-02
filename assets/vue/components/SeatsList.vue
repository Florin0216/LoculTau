<script setup>
import SeatItem from "./SeatItem.vue";
import {onMounted, ref} from "vue";
import axios from "axios";

const seats = ref({});

const selectedSeats = ref([]);

onMounted(() => {
    axios.get('/public/seats')
        .then(response => {
            seats.value = JSON.parse(response.data);
        });
});

function handleSelectedSeat({ seat, selected }) {
    if (selected) {
        selectedSeats.value.push(seat)
    } else {
        selectedSeats.value = selectedSeats.value.filter(s => s.id !== seat.id)
    }
}
</script>

<template>
    <div class="mb-10">
        <div class="space-y-1">
            <div
                v-for="row in 18"
                class="flex gap-0.5 md:gap-1 justify-center"
                :class="row === 11 ? 'mt-4 md:mt-6' : ''"
            >
                <SeatItem
                    v-for="seat in (row === 1 || row === 18 ? 20 : 22)"
                    :class="(seat === (row === 1 || row === 18 ? 10 : 11)) ? 'mr-3 md:mr-6' : ''"
                    :seat="seats[row]?.[seat]"
                    @selectSeat="handleSelectedSeat"
                />
            </div>
        </div>
    </div>
</template>
