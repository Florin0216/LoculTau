<script setup>

import {onMounted, ref} from "vue";
import axios from "axios";
import EventItem from "./EventItem.vue";

const events = ref([]);

onMounted(() => {
    axios.get('/fetchEvents')
        .then(response => {
            events.value = JSON.parse(response.data);
        });
});

</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-center w-10/12 mx-auto">
        <EventItem
            v-for="(event, index) in events"
            class="justify-self-center"
            :event="event"
        />
    </div>
</template>

