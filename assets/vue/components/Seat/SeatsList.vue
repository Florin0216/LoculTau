<script setup>
import SeatItem from "./SeatItem.vue";
import {onMounted, ref} from "vue";
import axios from "axios";

const seats = ref({});

const selectedSeats = ref([]);

onMounted(() => {
    axios.get('/seats')
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
        <div class="space-y-1 min-w-max">
            <div
                v-for="row in 18"
                class="flex gap-0.5 md:gap-1 justify-center"
                :class="row === 11 ? 'mt-4 md:mt-6' : ''"
            >
                <SeatItem
                    v-for="seat in (row === 1 || row === 18 ? 20 : 22)"
                    :class="(seat === (row === 1 || row === 18 ? 10 : 11)) ? 'mr-3 md:mr-6' : ''"
                    :seat="seats[row]?.[seat]"
                    @select="handleSelectedSeat"
                />
            </div>
        </div>
    </div>
    <div class="flex flex-col w-3/4 md:w-1/2 mx-auto p-5 rounded bg-gray-700">
        <div class="flex justify-around items-center mb-3">
            <div class="flex items-center gap-1">
                <div class="rounded-t-sm md:rounded-t-lg h-3 w-3 md:w-6 md:h-6 bg-gray-300"></div>
                <span class="text-sm md:text-lg text-white">Liber</span>
            </div>
            <div class="flex items-center gap-1">
                <div class="rounded-t-sm md:rounded-t-lg h-3 w-3 md:w-6 md:h-6 bg-red-600"></div>
                <span class="text-sm md:text-lg text-white">Ocupat</span>
            </div>
            <div class="flex items-center gap-1">
                <div class="rounded-t-sm md:rounded-t-lg h-3 w-3 md:w-6 md:h-6 bg-green-500"></div>
                <span class="text-sm md:text-lg text-white">Selectat</span>
            </div>
        </div>
        <div class="text-center text-white text-sm md:text-lg mb-5">
            Numar locuri selectate: {{selectedSeats.length}}
        </div>
        <div class="text-center">
            <button @click="" class="text-white hover:bg-blue-500 py-1.5 px-3 rounded bg-blue-600 text-sm md:text-lg">
                Confirma selectia
            </button>
        </div>
    </div>
</template>
