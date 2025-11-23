<script setup>
import { computed } from "vue";

const props = defineProps({
    seat: {
        type: Object,
        required: true,
    },
    isSelected: {
        type: Boolean,
        required: true,
    },
    sponsor: {
        type: Object,
        required: false
    }
});

const seatAppearance = computed(() => {
    if (!props.seat.isAvailable || (props.sponsor?.id && props.sponsor?.id !== props.seat.sponsor?.id)) {
        return {
            class: 'cursor-not-allowed',
            style: { backgroundColor: '#dc2626' }
        };
    }
    if (props.isSelected) {
        return {
            class: 'cursor-pointer',
            style: { backgroundColor: '#22c55e' }
        };
    }
    if (props.seat.sponsor?.color && props.sponsor?.id === props.seat.sponsor?.id) {
        return {
            class: 'cursor-pointer',
            style: { backgroundColor: props.seat.sponsor.color }
        };
    }
    return {
        class: 'cursor-pointer',
        style: { backgroundColor: '#d1d5db' }
    };
});
</script>

<template>
    <div
        v-if="seat"
        :id="seat.id"
        :class="['w-6 h-6 md:w-8 md:h-8 rounded-t-sm md:rounded-t-lg text-center text-[12px] md:text-xs', seatAppearance.class]"
        :style="seatAppearance.style"
    >
    <span v-if="seat.isAvailable && (props.sponsor?.id === props.seat.sponsor?.id) || seat.isAvailable && !props.sponsor?.id">
      {{ seat.number }}
    </span>
    </div>
</template>
