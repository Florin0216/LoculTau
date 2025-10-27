<script setup>

import {onMounted, ref} from "vue";
import EventItem from "./EventItem.vue";
import EventService from "../../services/EventService";

const events = ref([]);

onMounted(() => {
    EventService
        .getEvents()
        .then((response) => {
            events.value = response
        })
});

</script>

<template>
    <div class="grid grid-cols-1 gap-4 text-center w-10/12 mx-auto">
        <EventItem
            v-for="(event, index) in events"
            class="justify-self-center"
            :event="event"
        />
    </div>
</template>

