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
        <div class="w-xs md:w-md h-[500px] md:h-[700px] overflow-hidden rounded-xl">
            <a :href="Routing.generate('seating_room_show', { eventId: event.id, roomId: event.room.id })">
                <img v-if="event.image" :src="event.image.url" alt="logo"
                     class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110">
            </a>
        </div>
    </div>
</template>
