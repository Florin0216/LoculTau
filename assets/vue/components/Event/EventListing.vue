<script setup>

import {onMounted, ref} from "vue";
import EventItem from "./EventItem.vue";
import EventService from "../../services/EventService";

const events = ref([]);
const uuid = ref();

onMounted(() => {
    EventService
        .getEvents()
        .then((response) => {
            events.value = response
        })
    const params = new URLSearchParams(window.location.search);
    uuid.value = params.get('uuid');
});

</script>

<template>
    <div class="grid grid-cols-1 gap-4 text-center w-10/12 mx-auto">
        <EventItem
            v-for="(event, index) in events"
            class="justify-self-center"
            :event="event"
            :uuid="uuid"
        />
    </div>
</template>

