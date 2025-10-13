<script setup>

import {onMounted, reactive, ref, watch} from "vue";
import ReservationService from "../../../services/ReservationService";
import Pagination from "../../Common/Pagination.vue";
import PaginationModel from "../../../models/PaginationModel";
import PaginationResultsInfo from "../../Common/PaginationResultsInfo.vue";

const reservations = ref([]);

const pagination = reactive(new PaginationModel());

const searchKeyword = ref(null);

watch([() => pagination.page, () => pagination.itemsPerPage], (newValue, oldValue) => {
    getReservations(true);
})

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
                    <div>Rez. / pagină</div>
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

        <div v-if="pagination.page > 0" class="table-responsive">
            <table class="mb-0 table table-bordered">
                <thead>
                    <tr>
                        <th class="min-width-column">Id</th>
                        <th class="min-width-column">Uuid</th>
                        <th>Email</th>
                        <th>Nume</th>
                        <th>Eveniment</th>
                        <th>Scaun</th>
                        <th>Status</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="reservation in reservations">
                        <td>{{reservation.id}}</td>
                        <td>{{reservation.uuid}}</td>
                        <td>{{reservation.email}}</td>
                        <td>{{reservation.name}}</td>
                        <td>{{reservation.event.title}}</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <span>Rând:</span>
                                <span class="fw-bold">{{reservation.seat.rowNo}}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span>Loc:</span>
                                <span class="fw-bold">{{reservation.seat.number}}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span>Secțiune:</span>
                                <span class="fw-bold">{{reservation.seat.section}}</span>
                            </div>
                        </td>
                        <td>{{reservation.claimedAt ? 'Revendicat' : 'In asteptare'}}</td>
                    </tr>
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
