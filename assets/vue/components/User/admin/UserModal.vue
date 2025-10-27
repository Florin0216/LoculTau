<script setup>

import EditingButtonGroup from "../../Common/EditingButtonGroup.vue";
import RoomSelect from "../../Room/admin/RoomSelect.vue";
import {computed, onMounted, ref} from "vue";
import {isValue} from "../../../helpers/isValue";
import {getClone} from "../../../helpers/getClone";
import UserService from "../../../services/UserService";
import UserCreateDto from "../../../dto/User/UserCreateDto";
import UserEditDto from "../../../dto/User/UserEditDto";
import PasswordInput from "../../Common/PasswordInput.vue";

const props = defineProps({
    instance: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
    isEditing: {
        type: Boolean,
        required: false,
        default: false,
    }
});

const user = ref(getClone(props.user));

let userInitData = getClone(user.value);

const isEditing = ref(props.isEditing);

const passwordConfirmation = ref(null);

const isNewUser = computed(() => {
    return !isValue(props.user.id);
})

const isPasswordValid = computed(() => {
    if (passwordConfirmation.value === user.value.password) {
        return true;
    }

    if (passwordConfirmation.value === '' && !isValue(user.value.password) || !isValue(passwordConfirmation.value) && user.value.password === '') {
        return true;
    }

    if (!isValue(passwordConfirmation.value) && !isValue(user.value.password)) {
        return true;
    }

    return false;
})

const onEditConfirm = () => {
    const promise = isNewUser.value
        ? UserService.newSuperAdmin(new UserCreateDto(user.value))
        : UserService.editSuperAdmin(user.value.id, new UserEditDto(user.value));

    promise
        .then((response) => {
            user.value = response.data.data;

            isEditing.value = false;

            userInitData = getClone(user.value);
        })
        .catch(err => console.error(err));
}

const onEditCancel = () => {
    user.value = getClone(userInitData);

    isEditing.value = false;
}

const dismissModal = () => {
    props.instance.value.close(undefined);
}

const formPs = 2.7;

const iconClass = 'position-absolute top-50 start-0 translate-middle-y ms-3 fs-5';

</script>

<template>
    <div class="modal fade" tabindex="-1" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content pb-1">
                <div class="modal-header">
                    <h4 class="modal-title me-3">
                        <template v-if="isNewUser">Adaugă utilizator</template>
                        <template v-else>Detalii utilizator</template>
                    </h4>
                    <editing-button-group
                        v-model:is-editing="isEditing"
                        :font-size="5"
                        @confirm="onEditConfirm"
                        @cancel="onEditCancel"
                        :allow-confirmation="isPasswordValid"
                    > </editing-button-group>
                    <button @click="dismissModal" type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="position-relative mb-4">
                        <input v-model="user.username" :disabled="!isEditing" type="text" class="form-control " id="loginEmailInput" placeholder="Username" :style="`padding-left: ${formPs}rem`">

                        <i class="bi bi-card-text" :class="iconClass"></i>
                    </div>

                    <div class="position-relative mb-4">
                        <input v-model="user.email" :disabled="!isEditing" type="email" class="form-control " id="loginEmailInput" placeholder="Email" :style="`padding-left: ${formPs}rem`">

                        <i class="bi bi-envelope-at" :class="iconClass"></i>
                    </div>

                    <div class="position-relative mb-4">
                        <input v-model="user.firstName" :disabled="!isEditing" type="text" class="form-control " id="loginEmailInput" placeholder="Prenume" :style="`padding-left: ${formPs}rem`">

                        <i class="bi bi-person-vcard" :class="iconClass"></i>
                    </div>

                    <div class="position-relative mb-4">
                        <input v-model="user.lastName" :disabled="!isEditing" type="text" class="form-control " id="loginEmailInput" placeholder="Nume" :style="`padding-left: ${formPs}rem`">

                        <i class="bi bi-person-vcard" :class="iconClass"></i>
                    </div>

                    <div class="card border-gray-200" :style="{backgroundColor: !isEditing ? '#e9ecef' : ''}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-auto">
                                    <i class="bi bi-key me-1"></i>
                                    Permisiuni
                                </div>
                                <div class="col">
                                    <div v-for="(role, index) in ['ROLE_USER', 'ROLE_RESERVATION_VALIDATOR', 'ROLE_ADMIN']" :key="index" class="form-check">
                                        <input v-model="user.roles" :disabled="!isEditing" class="form-check-input" type="checkbox" :value="role" :id="role+index">
                                        <label class="form-check-label" for="checkDefault">
                                            {{role}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <template v-if="isEditing">
                        <password-input
                            v-model:value="user.password"
                            placeholder="Parolă"
                            class="my-4"
                        > </password-input>

                        <password-input
                            v-model:value="passwordConfirmation"
                            placeholder="Confirmare parolă"
                        > </password-input>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
