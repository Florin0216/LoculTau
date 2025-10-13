<script setup>

import {computed, onBeforeUnmount, onMounted, ref, watch} from "vue";
import ReservationService from "../../../services/ReservationService";
import {eventBus} from "../../../helpers/eventBus";

const props = defineProps({
    seat: {
        type: Object,
        required: true,
    },
    seatClass: {
        type: String,
        required: false,
    },
    eventId: {
        type: Number,
        required: false,
    },
    disableDropdown: {
        type: Boolean,
        required: false,
        default: false,
    }
})

const emits = defineEmits(['click'])

const showDropdown = ref(false);

const reservation = ref();

const dropdownRef = ref();

const seatRef = ref();

const dropdownPosition = computed(() => {
    const rect = seatRef.value.getBoundingClientRect();

    const spaceAbove = rect.top;
    const spaceBelow = window.innerHeight - rect.bottom;

    return spaceAbove > spaceBelow ? 'bottom-100' : 'top-100';
})

watch(showDropdown, (value, oldValue) => {
    if (value && reservation.value === undefined) {
        getReservation();
    }
})

const getReservation = () => {
    ReservationService
        .showAdmin('-', props.seat.id, props.eventId)
        .then((response) => {
            reservation.value = response.data.data;
        })
}

const handleClick = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        showDropdown.value = false;
    }
}

const onSeatClick = () => {
    showDropdown.value = !showDropdown.value

    eventBus.emit('dropdown', props.seat.id);

    emits('click');
}

onMounted(() => {
    document.addEventListener('click', handleClick);

    eventBus.on('dropdown', (seatId) => {
        if (props.seat.id !== seatId) {
            showDropdown.value = false;
        }
    })
})

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClick);
})

</script>

<template>
    <div class="position-relative">
        <div
            @click.stop="onSeatClick"
            ref="seatRef"
            class="rounded-circle p-1 border-gray-200 shadow-sm position-relative text-center cursor-pointer"
            :class="seatClass"
            style="width: 2rem; aspect-ratio: 1;"
        >
            {{seat.number}}
        </div>

        <div
            v-if="showDropdown && !seat.isAvailable && !disableDropdown"
            ref="dropdownRef"
            class="position-absolute start-50 border border-secondary-subtle shadow translate-middle-x bg-white rounded-3 p-3"
            :class="dropdownPosition"
            style="z-index: 3;"
        >
            <template v-if="reservation !== undefined">
                <h5 class="text-center mb-3 text-nowrap">Rând {{seat.rowNo}}, Loc {{seat.number}}</h5>

                <div class="d-flex align-items-center gap-1 text-secondary">
                    <i class="bi bi-person-vcard"></i>
                    <span>Nume</span>
                </div>
                <div class="mb-3 fw-medium">{{reservation?.name}}</div>

                <div class="d-flex align-items-center gap-1 text-secondary">
                    <span>@</span>
                    <span>Email</span>
                </div>
                <div class="fw-medium">{{reservation?.email}}</div>
            </template>
        </div>
    </div>

</template>

<style scoped>

</style>
