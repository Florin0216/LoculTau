<script setup>
import GalleryItemService from "../../services/GalleryItemService";
import {onMounted, ref, watch} from "vue";
import GalleryItem from "./GalleryItem.vue";
import GalleryService from "../../services/GalleryService";

const props = defineProps({
    events: {
        type: Array,
        required: true
    }
})

const galleryItems = ref([]);
const selectedEvent = ref(null);
const gallery = ref(null);

const getGalleryItems = () => {
    GalleryService.list(selectedEvent.value)
        .then(response => {
            gallery.value = response.data.data;
                return GalleryItemService.list(gallery.value);
        })
        .then(itemsResponse => {
            galleryItems.value = itemsResponse;
        })
}

watch(selectedEvent, () => {
    getGalleryItems();
});

onMounted(() => {
    getGalleryItems();
});

</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8 bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <label for="eventSelect" class="block text-sm font-semibold text-gray-700 mb-3">
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filtrează după eveniment
                    </span>
                </label>
                <select
                    v-model="selectedEvent"
                    id="eventSelect"
                    class="w-full md:w-96 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 bg-white text-gray-900 cursor-pointer hover:border-green-400"
                >
                    <option disabled>Toate evenimentele</option>
                    <option selected value="">General</option>
                    <option v-for="event in events" :key="event.id" :value="event.id">
                        {{ event.title }}
                    </option>
                </select>
            </div>

            <div class="mb-16">
                <div v-if="galleryItems.length"
                     class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div
                        v-for="(galleryItem, index) in galleryItems"
                    >
                        <gallery-item :gallery-item="galleryItem"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
