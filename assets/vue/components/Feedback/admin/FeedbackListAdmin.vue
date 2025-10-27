<script setup>
import {onMounted, ref} from "vue";
import FeedbackService from "../../../services/FeedbackService";

const feedbacks = ref({});

const getFeedbacks = () => {
    FeedbackService.listAdmin().then((response) => {
        feedbacks.value = response.data.data;
    })
}

onMounted(() => {
    getFeedbacks();
})
</script>

<template>
    <div class="container my-5">
        <h2 class="mb-4 text-center">Feedbacks</h2>

        <div class="row g-4">
            <template v-if="feedbacks.length > 0">
                <div class="col-md-6 col-lg-4" v-for="feedback in feedbacks" :key="feedback.id">
                    <div class="card h-100 shadow-lg border-1">
                        <div class="card-body">
                            <h5 class="card-title">{{ feedback.name }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ feedback.email }}</h6>
                            <p class="card-text">{{ feedback.message }}</p>
                        </div>
                    </div>
                </div>
            </template>

            <div v-else class="col-12 text-center">
                <p class="text-muted">No feedbacks available.</p>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
