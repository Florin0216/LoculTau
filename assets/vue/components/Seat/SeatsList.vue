<script setup>
import SeatItem from "./SeatItem.vue";
import {onActivated, onDeactivated, onMounted, ref, watch} from "vue";
import Reservation from "../Reservation/Reservation.vue";
import SeatService from "../../services/SeatService";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
    room: {
        type: Object,
        required: true
    },
    sponsor: {
        type: Object,
        required: false
    }
});

const seats = ref([]);

const selectedSeats = ref([]);

const singleForm = ref(false);

const showReservationForm = ref(false);

const isLoading = ref(false);

const onConfirmSelection = () => {

    for (let seat of selectedSeats.value) {
        if (leavesIsolatedSeats(seat)) {
            alert('Va rugam nu lasati loc liber cand selectati locurile!');
            return;
        }
    }

    showReservationForm.value = true;
}

watch(singleForm, (newValue, oldValue) => {
    if (newValue === oldValue) {
        return;
    }

    initReservationData()
})

const onSeatSelect = (seat) => {
    if (seat.isAvailable === false) {
        return;
    }

    if (isSelected(seat)) {
        selectedSeats.value = selectedSeats.value.filter(s => s.id !== seat.id);

        removeReservationSeatData(seat);

        seat.isSelected = false;
    } else {
        selectedSeats.value.push(seat);

        addReservationSeatData(seat);

        seat.isSelected = true;
    }
}

const addReservationSeatData = (seat) => {
    if (!singleForm.value) {
        reservationData.value.seatReservations.push({
            name: null,
            email: null,
            event: props.event.id,
            sponsor: props.sponsor?.id || null,
            seats: [seat.id],
        });
    } else {
        reservationData.value.seats.push(seat.id);
    }
}

const removeReservationSeatData = (seat) => {
    if (!singleForm.value) {
        const index = reservationData.value.seatReservations.findIndex(obj => obj.seats.includes(seat.id));
        if (index !== -1) {
            reservationData.value.seatReservations.splice(index, 1);
        }
    } else {
        const index = reservationData.value.seats.indexOf(seat.id);
        if (index !== -1) {
            reservationData.value.seats.splice(index, 1);
        }
    }

}


const isSelected = (seat) => {
    const index = selectedSeats.value.findIndex(obj => obj.id === seat.id);

    return index !== -1;
}

function getSeatCountForRow(row) {
    if (row === 1) return 22
    if (row === 2) return 25
    if (row === 3) return 28
    if (row === 4) return 31

    const middle = 10
    const seatsAtRow4 = 31
    const seatsAtMiddle = seatsAtRow4 + (middle - 4)

    if (row <= middle) {
        return 31 + (row - 4)
    }

    return seatsAtMiddle - (row - middle)
}

const reservationData = ref({});

const initReservationData = () => {
    reservationData.value = {};

    if (singleForm.value) {
        const seatIds = selectedSeats.value.map(seat => seat.id);

        reservationData.value = {
            name: null,
            email: null,
            event: props.event.id,
            sponsor: props.sponsor?.id || null,
            seats: seatIds,
        }
    } else {
        if (!singleForm.value) {
            reservationData.value.seatReservations = [];
        }

        for (let seat of selectedSeats.value) {
            reservationData.value.seatReservations.push({
                name: null,
                email: null,
                event: props.event.id,
                sponsor: props.sponsor?.id || null,
                seats: [seat.id],
            });
        }
    }
}

const getSeats = () => {
    isLoading.value = true;

    SeatService
        .getSeats(props.room, props.event)
        .then((response) => {
            seats.value = response;
        })
        .finally(() => {
            isLoading.value = false;
        })
}

const leavesIsolatedSeats = (seat) => {
    const seatCount = getSeatCountForRow(seat.rowNo);
    const rowSeats = seats.value[seat.section]?.[seat.rowNo];

    const simulatedRow = {};
    for (let i = 1; i <= seatCount; i++) {
        simulatedRow[i] = {...rowSeats[i]};
        if (i === seat.number) {
            simulatedRow[i].isAvailable = false;
        }
    }

    for (let i = 1; i <= seatCount; i++) {
        const current = simulatedRow[i];
        if (current.isAvailable && !current.isSelected) {
            const left = simulatedRow[i - 1];
            const right = simulatedRow[i + 1];

            const leftTaken = !left || !left.isAvailable || left.isSelected;
            const rightTaken = !right || !right.isAvailable || right.isSelected;

            if (leftTaken && rightTaken) {
                return true;
            }
        }
    }

    return false;
};

onMounted(() => {
    getSeats();

    initReservationData();
});

</script>

<template>
    <reservation
        v-if="showReservationForm"
        :reservation-data="reservationData"
        :seats="selectedSeats"
        :event="event"
        :room="room"
        :unique-reservation-data="singleForm"
        @back="showReservationForm = false"
    ></reservation>
    <KeepAlive>
        <div v-if="!showReservationForm" class="min-h-screen w-full flex flex-col items-center">
            <div
                class="bg-white w-full md:w-1/2 text-center rounded-2xl py-2 md:py-4 mb-5 md:mb-8 font-semibold text-lg md:text-2xl">
                Scena
            </div>
            <div class="mb-10 p-4 w-11/12 lg:w-2/3 overflow-x-scroll border border-white rounded-2xl"
                 :style="{height: isLoading ? '68.4vh' : ''}">
                <template v-if="!isLoading">
                    <div class="space-y-1 min-w-max">
                        <div
                            v-for="row in 18"
                            class="flex gap-1 justify-center items-center"
                        >
                            <div class="text-right mr-2 text-white text-sm font-semibold">
                                {{ String.fromCharCode(65 + (row - 1) + (row >= 17 ? 1 : 0)) }}
                            </div>
                            <template v-for="seat in getSeatCountForRow(row)">
                                <SeatItem
                                    v-if="seats['Sala Principala']?.[row]?.[seat]"
                                    :seat="seats['Sala Principala']?.[row]?.[seat]"
                                    :is-selected="isSelected(seats['Sala Principala']?.[row]?.[seat])"
                                    @click="onSeatSelect(seats['Sala Principala']?.[row]?.[seat])"
                                />
                            </template>

                            <div class="text-right ml-2 text-white text-sm font-semibold">
                                {{ String.fromCharCode(65 + (row - 1) + (row >= 17 ? 1 : 0)) }}
                            </div>
                        </div>
                        <div class="flex justify-evenly items-center mt-10 ">
                            <div>
                                <h3 class="text-center font-semibold text-white">Loja Stanga</h3>

                                <div v-for="row in 2" class="flex justify-center gap-1 mt-2">
                                    <template v-for="seatNumber in 4">
                                        <SeatItem
                                            v-if="seats['Loja Stanga']?.[row]?.[(row - 1) * 4 + seatNumber]"
                                            :seat="seats['Loja Stanga']?.[row]?.[(row - 1) * 4 + seatNumber]"
                                            :is-selected="isSelected(seats['Loja Stanga']?.[row]?.[(row - 1) * 4 + seatNumber])"
                                            @click="onSeatSelect(seats['Loja Stanga']?.[row]?.[(row - 1) * 4 + seatNumber])"
                                        />
                                    </template>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-center font-semibold text-white">Loja oficiala</h3>

                                <div v-for="row in 2" class="flex justify-evenly items-center gap-4 mt-2">
                                    <div class="flex justify-center gap-1">
                                        <template v-for="seatNo in 4">
                                            <SeatItem
                                                v-if="seats['Loja Oficiala Stanga']?.[row]?.[(row - 1) * 4 + seatNo]"
                                                :seat="seats['Loja Oficiala Stanga']?.[row]?.[(row - 1) * 4 + seatNo]"
                                                :is-selected="isSelected(seats['Loja Oficiala Stanga']?.[row]?.[(row - 1) * 4 + seatNo])"
                                                @click="onSeatSelect(seats['Loja Oficiala Stanga']?.[row]?.[(row - 1) * 4 + seatNo])"
                                            />
                                        </template>
                                    </div>
                                    <div class="flex justify-center gap-1 flex-row-reverse">
                                        <template v-for="seatNo in 4">
                                            <SeatItem
                                                v-if="seats['Loja Oficiala Dreapta']?.[row]?.[(row - 1) * 4 + seatNo]"
                                                :seat="seats['Loja Oficiala Dreapta']?.[row]?.[(row - 1) * 4 + seatNo]"
                                                :is-selected="isSelected(seats['Loja Oficiala Dreapta']?.[row]?.[(row - 1) * 4 + seatNo])"
                                                @click="onSeatSelect(seats['Loja Oficiala Dreapta']?.[row]?.[(row - 1) * 4 + seatNo])"
                                            />
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-center font-semibold text-white">Loja Dreapta</h3>

                                <div v-for="row in 2" class="flex flex-row-reverse justify-center gap-1 mt-2">
                                    <template v-for="seatNo in 4">
                                        <SeatItem
                                            v-if="seats['Loja Dreapta']?.[row]?.[(row - 1) * 4 + seatNo]"
                                            :seat="seats['Loja Dreapta']?.[row]?.[(row - 1) * 4 + seatNo]"
                                            :is-selected="isSelected(seats['Loja Dreapta']?.[row]?.[(row - 1) * 4 + seatNo])"
                                            @click="onSeatSelect(seats['Loja Dreapta']?.[row]?.[(row - 1) * 4 + seatNo])"
                                        />
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
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
                    Numar locuri selectate: {{ selectedSeats.length }}
                </div>

                <div v-if="selectedSeats.length >= 2" class="flex justify-center items-center gap-2 mb-4">
                    <input v-model="singleForm" type="checkbox" id="singleForm" class="w-4 h-4"/>

                    <label for="singleForm" class="text-white text-sm md:text-lg">
                        Toate locurile pe același nume și email
                    </label>
                </div>
                <div class="text-center">
                    <button
                        v-if="selectedSeats.length" @click="onConfirmSelection"
                        class="text-white hover:bg-blue-500 py-1.5 px-3 rounded bg-blue-600 text-sm md:text-lg"
                    >
                        Confirma selectia
                    </button>
                </div>
            </div>
        </div>
    </KeepAlive>
</template>
