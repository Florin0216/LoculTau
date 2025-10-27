<script setup>
import ReservationService from "../../services/ReservationService";
import ReservationForm from "./ReservationForm.vue";
import FosJsRouting from "../../../js/fosJsRouting";
import SeatService from "../../services/SeatService";
import {ref} from "vue";

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
    },
    room: {
        type: Object,
        required: true
    },
    event: {
        type: Object,
        required: true
    }
});

const emits = defineEmits(['back']);

const isSubmitting = ref(false);
const isConfirmed = ref(false);

const submitReservation = () => {
    if (isSubmitting.value) return;
    isSubmitting.value = true;

    if (props.uniqueReservationData) {
        ReservationService
            .new(reservationData.value)
            .then(() => {
                return SeatService.getSeats(props.room, props.event);
            })
            .then((response) => {
                props.seats.value = response;
                isSubmitting.value = false;
                window.location.href = FosJsRouting.generate('seating_reservation_show_reservation_success');
            })
    } else {
        const promises = [];

        for (let reservationSlot of reservationData.value.seatReservations) {
            promises.push(ReservationService.new(reservationSlot));
        }

        Promise.all(promises)
            .then(() => {
                return SeatService.getSeats(props.room, props.event);
            })
            .then((response) => {
                props.seats.value = response;
                isSubmitting.value = false;
                window.location.href = FosJsRouting.generate('seating_reservation_show_reservation_success');
            })
    }
}
</script>

<template>
    <div class="min-h-screen">
        <h2 class="text-3xl font-bold text-center text-white mb-5">Detalii rezervare</h2>
        <form @submit.prevent="submitReservation">
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
                        <div class="max-h-[70vh] overflow-y-auto border-2 border-white rounded-3xl p-4">
                            <template v-for="(reservationSlot, index) in reservationData.seatReservations" :key="index">
                                <reservation-form
                                    v-model:value="reservationData.seatReservations[index]"
                                    :seat-details="[props.seats[index]]"
                                ></reservation-form>
                            </template>
                        </div>
                    </template>
                </template>
            </div>

            <div class="flex justify-center items-center mt-4 text-white">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        v-model="isConfirmed"
                        class="w-4 h-4 accent-green-500 cursor-pointer"
                    />
                    <span>Confirm că informațiile introduse sunt corecte</span>
                </label>
            </div>

            <div class="flex justify-center items-center mt-6 gap-4">
                <div @click="!isSubmitting && emits('back')"
                     class="cursor-pointer border border-blue-500 text-white font-semibold px-6 py-2 rounded-lg">< Inapoi
                </div>
                <button
                    v-if="isConfirmed"
                    type="submit"
                    :disabled="isSubmitting"
                    class="bg-green-500 hover:bg-green-400 text-white font-semibold px-6 py-2 rounded-lg transition disabled:cursor-not-allowed"
                >
                    <span v-if="!isSubmitting">Finalizează rezervarea</span>
                    <span v-else>Se procesează...</span>
                </button>
            </div>
        </form>
    </div>
</template>
