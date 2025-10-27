<script setup>

import {computed} from "vue";

const props = defineProps({
    page: {
        type: Number,
        required: true,
    },
    itemCount: {
        type: Number,
        required: true,
    },
    perPage: {
        type: Number,
        required: true,
    }
})

const start = computed(() => {
    if (!props.itemCount) {
        return 0;
    }

    return props.page * props.perPage - props.perPage + 1;
});

const end = computed(() => {
    if (props.page * props.perPage > props.itemCount) {
        return props.itemCount;
    }

    return props.page * props.perPage;
});

</script>

<template>
    <span>Afișare de la {{start}} - {{end}} din {{props.itemCount}} rezultate</span>
</template>

<style scoped>

</style>
