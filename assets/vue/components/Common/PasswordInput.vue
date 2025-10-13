<script setup>

import {ref} from "vue";

const value = defineModel('value', {
    type: [String, null],
    required: false,
})

const props = defineProps({
    isDisabled: {
        type: Boolean,
        required: false,
        default: false,
    },
    placeholder: {
        type: String,
        required: false,
        default: ''
    }
});

const formPs = 2.7;

const iconClass = 'position-absolute top-50 start-0 translate-middle-y ms-3 fs-5';

const show = ref(false);

</script>

<template>
    <div class="position-relative">
        <input v-model="value" :disabled="isDisabled" :type="show ? 'text' : 'password'" class="form-control " :placeholder="placeholder" :style="`padding-left: ${formPs}rem`">

        <i class="bi bi-lock" :class="iconClass"></i>

        <div @click="show = !show" class="position-absolute end-0 top-50 translate-middle me-1">
            <i v-if="show" class="bi bi-eye-slash"></i>
            <i v-else class="bi bi-eye"></i>
        </div>
    </div>
</template>

<style scoped>

</style>
