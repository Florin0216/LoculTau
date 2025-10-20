<script setup>

import {useRooms} from "../../../composables/useRooms";
import ModalManager from "../../../services/ModalManager";
import RoomModal from "./RoomModal.vue";
import RoomModel from "../../../models/RoomModel";

const {rooms, getRooms} = useRooms();

const openRoomModal = (room = null, isEditing = false) => {
    ModalManager
        .open({
            component: RoomModal,
            props: {
                room: room ?? new RoomModel(),
                isEditing: isEditing,
            }
        })
        .then(() => {
            getRooms();
        })
}

</script>

<template>
    <div class="card rounded-4 overflow-hidden shadow-sm border-gray-200">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h3 class="fw-bold mb-0">Săli</h3>
                <button @click="openRoomModal(null, true)" class="ms-auto btn btn-primary rounded-4">
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
                        <th>Nume</th>
                        <th class="min-width-column">Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="room in rooms" :key="room.id">
                        <td>{{room.id}}</td>
                        <td>{{room.name}}</td>
                        <td>
                            <div class="d-flex-center gap-2">
                                <i @click="openRoomModal(room)" class="bi bi-eye"></i>
                                <i @click="openRoomModal(room, true)" class="bi bi-pencil text-primary"></i>
                                <i class="bi bi-trash text-danger"></i>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>

</style>
