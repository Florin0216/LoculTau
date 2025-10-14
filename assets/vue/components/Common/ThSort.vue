<script setup>

const sort = defineModel('sort', {
    required: true,
    type: Object,
})

const props = defineProps({
    field: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: false,
    }
})

const onThClick = (field) => {
    const sortDirMap = {
        asc: 'desc',
        desc: 'asc'
    }

    if (sort.value.field === field) {
        sort.value.direction = sortDirMap[sort.value.direction];
    } else {
        sort.value.field = field;
        sort.value.direction = 'asc';
    }
}

</script>

<template>
    <th class="min-width-column cursor-pointer" @click="onThClick(field)">
        <div class="d-flex align-items-center gap-1">
            <span>{{ label ?? field }}</span>
            <template v-if="sort.field === field">
                <i v-if="sort.direction === 'asc'" class="bi bi-caret-up-fill"></i>
                <i v-else class="bi bi-caret-down-fill"></i>
            </template>
        </div>
    </th>
</template>

<style scoped>

</style>
