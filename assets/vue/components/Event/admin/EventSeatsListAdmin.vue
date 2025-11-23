<script setup>

import {computed, onMounted, reactive, ref, watch} from "vue";
import SeatService from "../../../services/SeatService";
import SeatItemAdmin from "../../Seat/admin/SeatItemAdmin.vue";
import {eventBus} from "../../../helpers/eventBus";
import SponsorService from "../../../services/SponsorService";
import SeatEditDto from "../../../dto/Seat/SeatEditDto";

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

const selectedSponsor = ref();

const seats = reactive({});

const selectedSeats = ref([]);

const sponsors = ref({});

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

const getSponsorsForEvent = () => {
    SponsorService.eventSponsorsList(props.event.id).then((response) => {
        sponsors.value = response.data.data;
    })
}

const seatBg = (seat) => {
    if (isSelected(seat)) {
        return 'bg-success bg-opacity-75'
    }
    if (seat.sponsor?.color) {
        return '';
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

const onConfirm = () => {
    const requests = selectedSeats.value.map(seat =>
        SeatService.editAdmin(seat.id, new SeatEditDto({
            ...seat,
            room: seat.room.id,
            sponsor: selectedSponsor.value
        }))
    )

    Promise.all(requests)
        .then(() => {
            selectedSeats.value = [];
            selectedSponsor.value = null;
            return getSeats();
        })

}
const isSelected = (seat) => {
    const index = selectedSeats.value.findIndex(obj => obj.id === seat.id);

    return index !== -1;
}

onMounted(() => {
    getSeats();
    getSponsorsForEvent();
})

</script>

<template>
    <template v-if="!isLoading">
        <div v-if="sections.length" class="d-flex flex-column align-items-center flex-nowrap">
            <div v-for="row in sectionMaxRowMap['Sala Principala']" class="d-flex-center flex-nowrap" style="gap: 4px">
                <span class="me-4 fw-medium">{{ rowLetterMap[row - 1].toUpperCase() }}</span>

                <template v-for="number in sectionRowMaxNumberMap['Sala Principala'][row]">
                    <seat-item-admin
                        @click="onSeatClick(seats['Sala Principala'][row][number])"
                        :seat="seats['Sala Principala'][row][number]"
                        :seat-class="seatBg(seats['Sala Principala'][row][number])"
                        :event-id="event.id"
                        :disable-dropdown="seatSelectionEnabled"
                    ></seat-item-admin>
                </template>

                <span class="ms-4 fw-medium">{{ rowLetterMap[row - 1].toUpperCase() }}</span>
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
                        <div v-for="section in ['Loja Stanga', 'Loja Oficiala Stanga']" class="col-auto"
                             :class="{'ms-auto': section === 'Loja Oficiala Stanga'}">
                            <div v-for="row in sectionMaxRowMap[section]" class="d-flex-center flex-nowrap mb-1"
                                 style="gap: 4px">
                                <template
                                    v-for="number in Array.from({ length: sectionRowMaxNumberMap[section][row] - sectionRowMinNumberMap[section][row] + 1 }, (_, i) => i + sectionRowMinNumberMap[section][row])">
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
                        <div v-for="section in ['Loja Oficiala Dreapta', 'Loja Dreapta']" class="col-auto"
                             :class="{'ms-auto': section === 'Loja Dreapta'}">
                            <div v-for="row in sectionMaxRowMap[section]"
                                 class="d-flex-center flex-nowrap flex-row-reverse mb-1" style="gap: 4px">
                                <template
                                    v-for="number in Array.from({ length: sectionRowMaxNumberMap[section][row] - sectionRowMinNumberMap[section][row] + 1 }, (_, i) => i + sectionRowMinNumberMap[section][row])">
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
    <div v-if="seatSelectionEnabled && selectedSeats.length"
         class="position-fixed bottom-0 start-50 translate-middle-x p-3 bg-white shadow-lg rounded d-flex align-items-center gap-3"
         style="z-index: 2000;">
    <span class="fw-medium">
        {{ selectedSeats.length }}
    </span>
        <select v-model="selectedSponsor" class="form-select" style="width: 200px;">
            <option disabled value="">Selecteaza sponsor</option>
            <option
                v-for="s in sponsors"
                :key="s.id"
                :value="s.id"
            >
                {{ s.name }}
            </option>
        </select>
        <button
            class="btn btn-primary"
            @click="onConfirm"
        >
            Finalizeaza
        </button>

    </div>

</template>

<style scoped>

</style>
