<script setup>

import {onMounted, reactive, ref} from "vue";
import UserService from "../../../services/UserService";
import PaginationModel from "../../../models/PaginationModel";
import ModalManager from "../../../services/ModalManager";
import UserModal from "./UserModal.vue";
import UserModel from "../../../models/UserModel";
import UserTr from "./common/UserTr.vue";

const users = ref([]);

const pagination = reactive(new PaginationModel());

const getUsers = () => {
    UserService
        .listAdmin()
        .then((response) => {
            users.value = response.data.data;

            Object.assign(pagination, response.data.pagination);
        })
}

const openUserModal = (user = null, isEditing = false) => {
    ModalManager
        .open({
            component: UserModal,
            props: {
                user: user ?? new UserModel(),
                isEditing: isEditing,
            }
        })
        .then(() => {
            getUsers();
        })
}

const index = (user) => {
    return users.value.findIndex(obj => obj.id === user.id);
}

onMounted(() => {
    getUsers();
})

</script>

<template>
    <div class="card rounded-4 border-gray-200 shadow-sm overflow-hidden">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h3 class="mb-0">Staff</h3>

                <button @click="openUserModal(undefined, true)" class="ms-auto btn btn-primary rounded-4">
                    <i class="bi bi-plus-lg"></i>
                    Adaugă
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th class="min-width-column">Id</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Prenume</th>
                        <th>Nume</th>
                        <th>Permisiuni</th>
                        <th class="min-width-column">Dezactivat</th>
                        <th class="min-width-column">Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <user-tr
                        v-for="user in users"
                        :key="user.id"
                        v-model:user="users[index(user)]"
                        @show="openUserModal(user, false)"
                        @edit="openUserModal(user, true)"
                    > </user-tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>

</style>
