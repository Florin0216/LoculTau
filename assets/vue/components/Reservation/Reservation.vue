<script setup>
import {onMounted, ref, watch} from "vue";
import ReservationService from "../../services/ReservationService";
import ReservationForm from "./ReservationForm.vue";
import FosJsRouting from "../../../js/fosJsRouting";

const reservationData = defineModel('reservationData', {
    type: Object,
})

const props = defineProps({
    seats: {
        type: [Array, null],
        required: true,
    },
    uniqueReservationData: {
        type: Boolean,
        required: false,
        default: true,
    },
    reservationData: {
        type: Object,
        required: true
    }
});

const emits = defineEmits(['back']);

const submitReservation = () => {
    if (props.uniqueReservationData) {
        ReservationService
            .new(reservationData.value)
            .then((response) => {
                window.location.href = FosJsRouting.generate('seating_reservation_show_reservation_success');
            })
    } else {
        const promises = [];

        for (let reservationSlot of reservationData.value.seatReservations) {
            promises.push(ReservationService.new(reservationSlot));
        }

        Promise.all(promises)
            .then(() => {
                window.location.href = FosJsRouting.generate('seating_reservation_show_reservation_success');
            })
    }
}
</script>

<template>
    <h2 class="text-3xl font-bold text-center text-white mb-5">Detalii rezervare</h2>

    <div class="p-4 w-full md:w-1/2 mx-auto">
        <template v-if="reservationData">
            <template v-if="uniqueReservationData">
                <reservation-form
                    v-model:value="reservationData"
                    :seat-details="props.seats"
                    :show-seat-details="false"
                ></reservation-form>
            </template>
            <template v-else>
                <template v-for="(reservationSlot, index) in reservationData.seatReservations" :key="index">
                    <reservation-form
                        v-model:value="reservationData.seatReservations[index]"
                        :seat-details="[props.seats[index]]"
                    ></reservation-form>
                </template>
            </template>
        </template>
    </div>

    <div class="flex justify-center items-center mt-6 gap-4">
        <div @click="emits('back')"
             class="cursor-pointer border border-blue-500 text-white font-semibold px-6 py-2 rounded-lg">< Inapoi
        </div>
        <button
            @click="submitReservation"
            class="bg-green-500 hover:bg-green-400 text-white font-semibold px-6 py-2 rounded-lg transition"
        >
            Finalizează rezervarea
        </button>
    </div>
</template>
