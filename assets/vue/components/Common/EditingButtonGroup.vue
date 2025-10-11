<script setup>

import {computed} from "vue";

const isEditing = defineModel('isEditing', {
    required: true,
})

const props = defineProps({
    fontSize: {
        required: false,
        type: Number,
        default: 6,
        validator(value) {
            return value >= 1 && value <= 6
        }
    }
});

const emit = defineEmits(['confirm', 'cancel'])

const fs = computed(() => {
    if (props.fontSize === 6) {
        return '';
    }

    return 'fs-' + props.fontSize;
})

const cancelEdit = () => {
    isEditing.value = false;

    emit('cancel');
}

</script>

<template>
    <Transition name="slide-up" mode="out-in">
        <span v-if="!isEditing" @click="isEditing = true" class="text-primary" :class="fs" style="cursor: pointer;">
            <i class="bi bi-pencil"></i>
        </span>
        <div v-else class="d-flex align-items-center gap-3" :class="fs">
            <span @click="emit('confirm')" class="text-success">
                <i class="bi bi-check-lg"></i>
            </span>
            <span @click="cancelEdit" class="text-danger">
                <i class="bi bi-x-lg"></i>
            </span>
        </div>
    </Transition>
</template>

<style scoped>

</style>
