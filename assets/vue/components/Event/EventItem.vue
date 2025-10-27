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
    <div v-if="event.room?.id" class="flex flex-col w-auto  md:w-1/2 bg-white p-4 rounded-3xl">
        <a :href="Routing.generate('seating_room_show', { eventId: event.id, roomId: event.room.id })">
            <div class="relative overflow-hidden rounded-3xl group">
                <img v-if="event.image" :src="event.image.url" alt="logo"
                     class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110">
                <div
                    class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/80 via-black/60 to-transparent text-white p-6">
                    <h2 class="text-2xl font-semibold tracking-wide">Următorul eveniment</h2>
                </div>
            </div>

            <div v-if="event.sponsors?.length" class="mt-6">
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Sponsori</h3>
                <div class="flex overflow-x-auto space-x-4 pb-2">
                    <div v-for="sponsor in event.sponsors" :key="sponsor.id"
                         class="flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden shadow-sm">
                        <img v-if="sponsor.image" :src="sponsor.image.url" :alt="sponsor.name" class="w-full h-full">
                    </div>
                </div>
            </div>
        </a>
    </div>
</template>

