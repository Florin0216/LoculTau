<script setup>

import Routing from "fos-router";

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});

</script>

<template>
    <div v-if="event.room?.id" class="flex flex-col w-auto group md:w-lg">
        <div class="w-auto h-auto overflow-hidden rounded-t-xl">
            <a :href="Routing.generate('seating_room_show', { eventId: props.event.id, roomId: props.event.room.id })">
                <img src="https://picsum.photos/700/800" alt="logo"
                     class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110">
            </a>
        </div>
        <div class="bg-white p-6 text-center rounded-b-xl">
            <div class="font-semibold text-2xl mb-5">
                {{ event.title }}
            </div>
            <a :href="Routing.generate('seating_room_show', { eventId: props.event.id, roomId: props.event.room.id })"
               class="text-blue-500 border border-blue-500 hover:text-white hover:bg-blue-500 py-1.5 px-3 rounded bg-white text-sm md:text-lg transition duration-500"
            >Detalii
            </a>
        </div>
    </div>
</template>
