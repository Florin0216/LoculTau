<script setup>
import {onMounted, ref, watch} from "vue";
import {isValue} from "../../../helpers/isValue";
import EventService from "../../../services/EventService";

const selectedEvent = defineModel('selectedEvent', {
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

const events = ref([]);

const getEvents = () => {
    EventService
        .listAdmin()
        .then((response) => {
            events.value = response.data.data;
        })
}

const selectedEventId = ref(selectedEvent.value?.id);

watch(selectedEventId, () => {
    if (!isValue(selectedEventId.value)) {
        return;
    }

    const index = events.value.findIndex(obj => obj.id === selectedEventId.value);

    if (index !== -1) {
        selectedEvent.value = events.value[index];
    }

    console.log(selectedEventId)
})

const iconClass = 'position-absolute top-50 start-0 translate-middle-y ms-3 fs-5';

const formPs = 2.7 //rem

onMounted(() => {
    getEvents();
})

</script>

<template>
    <div class="position-relative">
        <select
            v-model="selectedEventId"
            class="form-select"
            :style="`padding-left: ${formPs}rem`"
            :disabled="isDisabled"
        >
            <option disabled value="">Selectează evenimentul</option>
            <option v-for="event in events" :key="event.id" :value="event.id">
                {{event.title}}
            </option>
        </select>

        <i class="bi bi-building" :class="iconClass"></i>
    </div>
</template>

<style scoped>

</style>
