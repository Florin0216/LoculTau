<script setup>

import {computed, onMounted, reactive, ref, watch} from "vue";
import SeatService from "../../../services/SeatService";
import SeatItemAdmin from "../../Seat/admin/SeatItemAdmin.vue";
import {eventBus} from "../../../helpers/eventBus";

const seatSelectionEnabled = defineModel('seatSelectionEnabled', {
    type: Boolean,
    required: false,
    default: false,
});

const props = defineProps({
    event: {
        type: Object,
        required: true,
    }
});

const isLoading = ref(false);

const seats = reactive({});

const selectedSeats = ref([]);


const sections = ref([]);

const sectionRowMaxNumberMap = reactive({});

const sectionRowMinNumberMap = reactive({});

const sectionMaxRowMap = reactive({});

const rowLetterMap = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's'];

watch(seatSelectionEnabled, (newValue, oldValue) => {
    if (!seatSelectionEnabled.value) {
        selectedSeats.value = [];
    }
});

const getSeats = () => {
    isLoading.value = true;

    SeatService
        .getSeats(props.event.room, props.event, {normalListing: true})
        .then((response) => {
            for (let seat of response) {
                const section = seat.section;
                const row = seat.rowNo;
                const number = seat.number;

                if (!seats[section]) {
                    seats[section] = {};
                }

                if (!seats[section][row]) {
                    seats[section][row] = {};
                }

                seats[section][row][number] = seat;

                if (!sections.value.includes(section)) {
                    sections.value.push(section);
                }

                sectionMaxRowMap[section] = Math.max(sectionMaxRowMap[section] ?? row, row);

                sectionRowMaxNumberMap[section] ??= {};
                sectionRowMaxNumberMap[section][row] = Math.max(sectionRowMaxNumberMap[section][row] ?? 1, number);

                sectionRowMinNumberMap[section] ??= {};
                sectionRowMinNumberMap[section][row] = Math.min(sectionRowMinNumberMap[section][row] ?? Infinity, number);

            }
        })
        .finally(() => {
            isLoading.value = false;
        })
}

const seatBg = (seat) => {
    if (isSelected(seat)) {
        return 'bg-success bg-opacity-75'
    }

    if (seat.isAvailable) {
        return 'bg-secondary-subtle';
    }

    if (seat.claimedAt) {
        return 'bg-primary bg-opacity-50'
    }

    return 'bg-danger bg-opacity-75';
}

const onSeatClick = (seat) => {
    if (!seatSelectionEnabled.value) {
        return;
    }

    const index = selectedSeats.value.findIndex(obj => obj.id === seat.id);

    if (!isSelected(seat)) {
        selectedSeats.value.push(seat);
    } else {
        selectedSeats.value.splice(index, 1);
    }
}

const isSelected = (seat) => {
    const index = selectedSeats.value.findIndex(obj => obj.id === seat.id);

    return index !== -1;
}

onMounted(() => {
    getSeats();
})

</script>

<template>
    <template v-if="!isLoading">
        <div v-if="sections.length" class="d-flex flex-column align-items-center flex-nowrap">
            <div v-for="row in sectionMaxRowMap['main']" class="d-flex-center flex-nowrap" style="gap: 4px">
                <span class="me-4 fw-medium">{{rowLetterMap[row - 1].toUpperCase()}}</span>

                <template v-for="number in sectionRowMaxNumberMap['main'][row]">
                    <seat-item-admin
                        @click="onSeatClick(seats['main'][row][number])"
                        :seat="seats['main'][row][number]"
                        :seat-class="seatBg(seats['main'][row][number])"
                        :event-id="event.id"
                        :disable-dropdown="seatSelectionEnabled"
                    ></seat-item-admin>
                </template>

                <span class="ms-4 fw-medium">{{rowLetterMap[row - 1].toUpperCase()}}</span>
            </div>

            <div class="row w-100 mt-5 justify-content-center mb-2">
                <div class="col-10 d-flex justify-content-between align-items-center">
                    <div>Loja stângă</div>
                    <div>Loja centrală</div>
                    <div>Loja dreaptă</div>
                </div>
            </div>

            <div class="row w-100 justify-content-center">
                <div class="col-5">
                    <div class="row">
                        <div v-for="section in ['lodge_left', 'lodge_middle_left']" class="col-auto" :class="{'ms-auto': section === 'lodge_middle_left'}">
                            <div v-for="row in sectionMaxRowMap[section]" class="d-flex-center flex-nowrap mb-1" style="gap: 4px">
                                <template v-for="number in Array.from({ length: sectionRowMaxNumberMap[section][row] - sectionRowMinNumberMap[section][row] + 1 }, (_, i) => i + sectionRowMinNumberMap[section][row])">
                                    <seat-item-admin
                                        @click="onSeatClick(seats[section][row][number])"
                                        :seat="seats[section][row][number]"
                                        :seat-class="seatBg(seats[section][row][number])"
                                        :event-id="event.id"
                                        :disable-dropdown="seatSelectionEnabled"
                                    ></seat-item-admin>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="row">
                        <div v-for="section in ['lodge_middle_right', 'lodge_right']" class="col-auto" :class="{'ms-auto': section === 'lodge_right'}">
                            <div v-for="row in sectionMaxRowMap[section]" class="d-flex-center flex-nowrap flex-row-reverse mb-1" style="gap: 4px">
                                <template v-for="number in Array.from({ length: sectionRowMaxNumberMap[section][row] - sectionRowMinNumberMap[section][row] + 1 }, (_, i) => i + sectionRowMinNumberMap[section][row])">
                                    <seat-item-admin
                                        @click="onSeatClick(seats[section][row][number])"
                                        :seat="seats[section][row][number]"
                                        :seat-class="seatBg(seats[section][row][number])"
                                        :event-id="event.id"
                                        :disable-dropdown="seatSelectionEnabled"
                                    ></seat-item-admin>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <div v-else class="h-100 d-flex-center" style="min-height: 69.2vh">
        <div class="spinner-border text-primary" role="status" style="width: 5rem; height: 5rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</template>

<style scoped>

</style>
