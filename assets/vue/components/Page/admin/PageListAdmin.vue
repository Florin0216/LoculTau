<script setup>

import {onMounted, ref} from "vue";
import ModalManager from "../../../services/ModalManager";
import PageService from "../../../services/PageService";
import PageModal from "./PageModal.vue";
import PageModel from "../../../models/PageModel";


const pages = ref([]);
const getPages = () => {
    PageService
        .listAdmin()
        .then((response) => {
            pages.value = response.data.data;
        })
}

const openPageModal = (page = null, isEditing = false) => {
    ModalManager
        .open({
            component: PageModal,
            props: {
                page: page ?? new PageModel(),
                isEditing: isEditing,
            }
        })
        .then(() => {
            getPages();
        })
}

onMounted(() => {
    getPages()
})

</script>

<template>
    <div class="card rounded-4 overflow-hidden shadow-sm border-gray-200">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h3 class="fw-bold mb-0">Continutul paginilor</h3>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                <tr>
                    <th class="min-width-column">Id</th>
                    <th>Titlu</th>
                    <th>Sectiune</th>
                    <th>Sectiune</th>
                    <th class="min-width-column">Acțiuni</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="page in pages" :key="page.id">
                    <td>{{page.id}}</td>
                    <td>{{page.title}}</td>
                    <td>{{page.slug}}</td>
                    <td>{{page.section}}</td>
                    <td>
                        <div class="d-flex-center gap-2">
                            <i @click="openPageModal(page)" class="bi bi-eye"></i>
                            <i @click="openPageModal(page, true)" class="bi bi-pencil text-primary"></i>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
