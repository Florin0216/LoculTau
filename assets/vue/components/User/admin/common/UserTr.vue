<script setup>

import {watch} from "vue";
import UserService from "../../../../services/UserService";
import UserDisableDto from "../../../../dto/User/UserDisableDto";

const user = defineModel('user', {
    type: Object,
    required: true
});

const emits = defineEmits(['refresh', 'show', 'edit']);

watch(() => user.value.isDisabled, (newValue, oldValue) => {
    if (newValue === oldValue) {
        return;
    }

    UserService
        .editSuperAdmin(user.value.id, new UserDisableDto(user.value))
        .then((response) => {
            user.value = response.data.data;
        })
})

</script>

<template>
    <tr>
        <td>{{user.id}}</td>
        <td>{{user.username}}</td>
        <td>{{user.email}}</td>
        <td>{{user.firstName}}</td>
        <td>{{user.lastName}}</td>
        <td>
            <div v-for="role in user.roles" class="d-flex align-items-center gap-2">
                <i class="bi bi-dot"></i>
                <span>{{role}}</span>
            </div>
        </td>
        <td>
            <div class="d-flex-center">
                <div class="form-check form-switch">
                    <input
                        v-model="user.isDisabled"
                        class="form-check-input"
                        type="checkbox" role="switch"
                        id="switchCheckDefault"
                    >
                </div>
            </div>
        </td>
        <td>
            <div class="d-flex-center gap-2">
                <i @click="emits('show')" class="bi bi-eye cursor-pointer"></i>
                <i @click="emits('edit')" class="bi bi-pencil text-primary cursor-pointer"></i>
                <i class="bi bi-trash text-danger cursor-pointer"></i>
            </div>
        </td>
    </tr>
</template>

<style scoped>

</style>
