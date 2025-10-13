<script setup>

const page = defineModel('page', {
    type: Number,
    required: true,
})

const props = defineProps({
    itemCount: {
        type: Number,
        required: true,
    },
    perPage: {
        type: Number,
        required: true,
    },
    pageCount: {
        type: Number,
        required: true,
    }
})

const size = 'btn';

</script>

<template>
    <div class="d-flex align-items-center">
        <button @click="page = 1" :disabled="page === 1" class="btn btn-outline-primary border-gray-200 rounded-end-0 rounded-start-3" :class="size">
            <i class="bi bi-chevron-double-left"></i>
        </button>
        <button @click="page--" :disabled="page === 1" class="btn btn-outline-primary border-gray-200 rounded-end-0 rounded-0 border-start-0" :class="size">
            <i class="bi bi-chevron-left"></i>
        </button>

        <button v-if="page - 2 > 0" disabled class="btn border-gray-200 rounded-0" :class="size">
            <i class="bi bi-three-dots"></i>
        </button>

        <button @click="page--" v-if="page - 1 > 0" class="btn border-gray-200 rounded-0 border-start-0" :class="size">
            {{page - 1}}
        </button>

        <button class="btn btn-primary rounded-0 border-start-0" :class="size">
            {{page}}
        </button>

        <button @click="page++" v-if="page + 1 <= pageCount" class="btn border-gray-200 rounded-0 border-start-0" :class="size">
            {{page + 1}}
        </button>

        <button v-if="page + 2 <= pageCount" disabled class="btn border-gray-200 rounded-0" :class="size">
            <i class="bi bi-three-dots"></i>
        </button>

        <button @click="page++" :disabled="page === pageCount" class="btn btn-outline-primary border-gray-200 rounded-start-0 rounded-end-0 border-end-0" :class="size">
            <i class="bi bi-chevron-right"></i>
        </button>
        <button @click="page = pageCount" :disabled="page === pageCount" class="btn btn-outline-primary border-gray-200 rounded-start-0 rounded-0 rounded-end-3" :class="size">
            <i class="bi bi-chevron-double-right"></i>
        </button>
    </div>
</template>

<style scoped>

</style>
