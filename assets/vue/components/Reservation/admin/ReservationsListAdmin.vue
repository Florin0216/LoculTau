<script setup>

import {onMounted, reactive, ref, watch} from "vue";
import ReservationService from "../../../services/ReservationService";
import Pagination from "../../Common/Pagination.vue";
import PaginationModel from "../../../models/PaginationModel";
import PaginationResultsInfo from "../../Common/PaginationResultsInfo.vue";
import {isValue} from "../../../helpers/isValue";
import ThSort from "../../Common/ThSort.vue";
import ReservationTrItem from "./common/ReservationTrItem.vue";

const reservations = ref([]);

const pagination = reactive(new PaginationModel());

const searchKeyword = ref(null);

const collapseUuid = ref(false);

const sort = reactive({
    direction: null,
    field: null,
})

watch([() => pagination.page, () => pagination.itemsPerPage, sort], (newValue, oldValue) => {
    getReservations(true);
}, {deep: true})

let timeout = null;

watch(searchKeyword, () => {
    if (timeout) {
        clearTimeout(timeout);
    }

    timeout = setTimeout(() => {
        getReservations(true);
    }, 500)
})

const getReservations = (withPagination = false) => {
    let args = {
        keyword: searchKeyword.value
    };

    if (isValue(sort.field)) {
        args.sort = sort.field;
        args.direction = sort.direction;
    }

    if (withPagination) {
        args.page = pagination.page ?? 1;
        args.limit = pagination.itemsPerPage;
    }

    ReservationService
        .listAdmin(args)
        .then((response) => {
            reservations.value = response.data.data;

            Object.assign(pagination, response.data.pagination);
        })
}

const onDelete = (reservation) => {
    const index = reservations.value.findIndex(obj => obj.id === reservation.id);

    reservations.value.splice(index, 1);
}

onMounted(() => {
    getReservations();
})

</script>

<template>
    <div class="card rounded-4 border-gray-200 shadow-sm overflow-hidden">
        <div class="card-body">
            <div class="row align-items-center mb-3 g-1">
                <div class="col-auto">
                    <h3 class="fw-bold mb-0">Rezervări</h3>
                </div>
                <div class="ms-auto col-4 col-lg-2 col-xxl-1">
                    <div class="mb-1">Rez. / pagină</div>
                    <select v-model="pagination.itemsPerPage" class="form-select">
                        <option disabled value="">Rezultate pe pagina</option>
                        <option
                            v-for="value in [1, 5, 8, 10, 25, 50, 100]"
                            :key="value"
                            :value="value"
                        > {{value}} </option>
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input v-model="searchKeyword" type="text" class="form-control" placeholder="Cuvinte cheie..." aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>

            <div class="row text-secondary">
                <div class="col-12 col-lg-4">
                    <pagination-results-info
                        :per-page="pagination.itemsPerPage"
                        :item-count="pagination.itemCount"
                        :page="pagination.page"
                    > </pagination-results-info>
                </div>
            </div>

        </div>

        <div class="table-responsive">
            <table class="mb-0 table table-bordered">
                <thead>
                    <tr class="align-middle">
                        <th-sort v-model:sort="sort" field="id" label="Id"></th-sort>
                        <th class="min-width-column">
                            <div class="d-flex align-items-center gap-1">
                                <span>Uuid</span>
                                <span @click="collapseUuid = !collapseUuid" class="ms-auto btn btn-sm btn-light border-secondary-subtle p-1">
                                    <i v-if="!collapseUuid" class="bi bi-chevron-left"></i>
                                    <i v-else class="bi bi-chevron-right"></i>
                                </span>
                            </div>
                        </th>
                        <th-sort v-model:sort="sort" field="updatedAt" label="Actualizat la"> </th-sort>
                        <th>Email</th>
                        <th>Nume</th>
                        <th>Eveniment</th>
                        <th>Scaun</th>
                        <th>Status</th>
                        <th class="min-width-column">Acțiuni</th>
                    </tr>
                </thead>
                <tbody v-if="pagination.page > 0">
                    <reservation-tr-item
                        v-for="reservation in reservations"
                        :key="reservation.id"
                        :reservation="reservation"
                        :collapse-uuid="collapseUuid"
                        @delete="onDelete(reservation)"
                    > </reservation-tr-item>
                </tbody>
            </table>
        </div>

        <div class="d-flex-center my-3">
            <pagination
                v-model:page="pagination.page"
                :item-count="pagination.itemCount"
                :per-page="pagination.itemsPerPage"
                :page-count="pagination.pageCount"
            > </pagination>
        </div>
    </div>
</template>

<style scoped>

</style>
