<script setup>
import {computed} from "vue";

const props = defineProps({
    seat: {
        type: Object,
        required: true,
    },
    isSelected: {
        type: Boolean,
        required: true,
    }
});

const seatColor = computed(() => {
    if (!props.seat.isAvailable) return 'bg-red-600 cursor-not-allowed';

    if (props.isSelected) return 'bg-green-500 cursor-pointer'
    return 'bg-gray-300 cursor-pointer'
});

</script>

<template>
    <div
        v-if="seat"
        :id="seat.id"
        :class="['w-3.5 h-3.5 md:w-6 md:h-6 rounded-t-sm md:rounded-t-lg bg-gray-300 text-center text-[10px] md:text-xs',seatColor]"
    >
        <span v-if="seat.isAvailable">{{ seat.number }}</span>
    </div>
</template>
