<script setup>

import {ref} from "vue";
import FeedbackService from "../../services/FeedbackService";
import FeedbackCreateDto from "../../dto/Feedback/FeedbackCreateDto";
import {useReCaptcha, VueReCaptcha} from "vue-recaptcha-v3";
import app from "../../instance";

const feedbackData = ref({});

const successMessage = ref("");

const { executeRecaptcha } = useReCaptcha();
const onSubmit = async () => {
    try {
        const token = await executeRecaptcha('feedback_form');

        const payload = new FeedbackCreateDto({
            ...feedbackData.value,
            recaptchaToken: token,
        });

        console.log(token)

        await FeedbackService.new(payload);

        successMessage.value = "Mesajul tău a fost trimis cu succes!";
        feedbackData.value = {};

        setTimeout(() => {
            successMessage.value = "";
        }, 3000);
    } catch (error) {
        console.error("Error submitting feedback:", error);
    }
};
</script>

<template>
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Lasa un mesaj</h2>
        <div v-if="successMessage" class="mb-4 p-4 text-green-800 bg-green-100 rounded-lg">
            {{ successMessage }}
        </div>
        <form @submit.prevent="onSubmit" id="contactForm" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nume complet: <span class="text-red-500">*</span></label>
                <input v-model="feedbackData.name" type="text" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent transition" placeholder="Popescu Ion">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Adresa de mail: <span class="text-red-500">*</span></label>
                <input v-model="feedbackData.email" type="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent transition" placeholder="exemplu@gmail.com">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mesaj: <span class="text-red-500">*</span></label>
                <textarea v-model="feedbackData.message" required rows="10" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent transition" placeholder="Scrie aici mesajul tau..."></textarea>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg transition transform hover:scale-105">
                Trimite
            </button>
        </form>
    </div>
</template>

<style scoped>

</style>
