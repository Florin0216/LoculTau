<script setup>

import SponsorService from "../../../services/SponsorService";
import {onMounted, ref} from "vue";
import ModalManager from "../../../services/ModalManager";
import SponsorModal from "./SponsorModal.vue";
import SponsorModel from "../../../models/SponsorModel";


const sponsors = ref([]);
const getSponsors = () => {
    SponsorService
        .listAdmin()
        .then((response) => {
            sponsors.value = response.data.data;
        })
}

const openSponsorModal = (sponsor = null, isEditing = false) => {
    ModalManager
        .open({
            component: SponsorModal,
            props: {
                sponsor: sponsor ?? new SponsorModel(),
                isEditing: isEditing,
            }
        })
        .then(() => {
            getSponsors();
        })
}

onMounted(() => {
    getSponsors()
})

</script>

<template>
    <div class="card rounded-4 overflow-hidden shadow-sm border-gray-200">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h3 class="fw-bold mb-0">Sponsori</h3>
                <button @click="openSponsorModal(null, true)" class="ms-auto btn btn-primary rounded-4">
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
                <tr v-for="sponsor in sponsors" :key="sponsor.id">
                    <td>{{sponsor.id}}</td>
                    <td>{{sponsor.name}}</td>
                    <td>
                        <div class="d-flex-center gap-2">
                            <i @click="openSponsorModal(sponsor)" class="bi bi-eye"></i>
                            <i @click="openSponsorModal(sponsor, true)" class="bi bi-pencil text-primary"></i>
                            <i class="bi bi-trash text-danger"></i>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
