<script setup>
const props = defineProps({
    galleryItem: {
        type: Object,
        required: true,
    }
});
</script>

<template>
    <div class="relative group rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer">
        <img
            :src="galleryItem.thumbnail.url"
            alt="Gallery Photo"
            class="w-full h-full rounded-2xl"
        />
    </div>
</template>

<style scoped>

</style>
