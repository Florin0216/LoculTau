<script setup>
import {computed, ref} from "vue";

const props = defineProps({
    seat: {type: Object, required: true, default: () => ({}) }
});

const emits = defineEmits(['selectSeat']);

const isPressed = ref(false);

const seatColor = computed(() => {
    if (isPressed.value) return 'bg-green-500'
    return 'bg-gray-300'
});

function selectSeat() {
    isPressed.value = !isPressed.value
    emits('selectSeat',{ seat: props.seat, selected: isPressed.value })
}
</script>

<template>
    <div
        :id="seat.id"
        :class="['w-3.5 h-3.5 md:w-6 md:h-6 rounded-t-sm md:rounded-t-lg bg-gray-300 cursor-pointer text-center text-[10px] md:text-xs',seatColor]"
        @click="selectSeat"
    >
        {{ seat.seatNo }}
    </div>
</template>
