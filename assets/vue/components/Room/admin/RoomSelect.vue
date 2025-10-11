<script setup>

import {useRooms} from "../../../composables/useRooms";
import {ref, watch} from "vue";
import {isValue} from "../../../helpers/isValue";

const selectedRoom = defineModel('selectedRoom', {
    type: Object,
    required: true,
});

const props = defineProps({
    isDisabled: {
        type: Boolean,
        required: false,
        default: false,
    }
});

const {rooms, getRooms} = useRooms();

const selectedRoomId = ref(selectedRoom.value?.id);

watch(selectedRoomId, () => {
    if (!isValue(selectedRoomId.value)) {
        return;
    }

    const index = rooms.value.findIndex(obj => obj.id === selectedRoomId.value);

    if (index !== -1) {
        selectedRoom.value = rooms.value[index];
    }
})

const iconClass = 'position-absolute top-50 start-0 translate-middle-y ms-3 fs-5';

const formPs = 2.7 //rem

</script>

<template>
    <div class="position-relative">
        <select
            v-model="selectedRoomId"
            class="form-select"
            :style="`padding-left: ${formPs}rem`"
            :disabled="isDisabled"
        >
            <option disabled value="">Selectează sala</option>
            <option v-for="room in rooms" :key="room.id" :value="room.id">
                {{room.name}}
            </option>
        </select>

        <i class="bi bi-building" :class="iconClass"></i>
    </div>
</template>

<style scoped>

</style>
