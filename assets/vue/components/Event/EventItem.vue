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
    <div v-if="event.room?.id" class="flex flex-col w-auto group md:w-1/2">
        <div class="relative overflow-hidden rounded-3xl">
            <a :href="Routing.generate('seating_room_show', { eventId: event.id, roomId: event.room.id })">
                <img v-if="event.image" :src="event.image.url" alt="logo"
                     class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110">
                <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/80 via-black/60 to-transparent text-white p-6">
                    <h2 class="text-2xl font-semibold tracking-wide">Următorul eveniment</h2>
                </div>

            </a>
        </div>
    </div>
</template>
