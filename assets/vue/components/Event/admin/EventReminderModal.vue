<script setup>
import {computed, onMounted, ref} from "vue";
import ReservationService from "../../../services/ReservationService";
import ReminderService from "../../../services/ReminderService";

const props = defineProps({
    instance: {
        type: Object,
        required: true,
    },
    event: {
        type: Object,
        required: true,
    }
});

const reminderOption = ref();

const customHours = ref();

const reservations = ref([]);

const dismissModal = () => {
    props.instance.value.close(undefined);
}

const uniqueEmails = computed(() => {
    const emails = reservations.value.map(r => r.email);

    return [...new Set(emails)];
})

const reminderDateTime = computed(() => {
    let date;

    if (reminderOption.value === 'now') {
        date = new Date();
    } else if (reminderOption.value === 'custom' && customHours.value) {
        const eventDate = new Date(props.event.date);
        const hoursInMs = customHours.value * 60 * 60 * 1000;
        date = new Date(eventDate.getTime() - hoursInMs);
    }

    return date.toISOString();
});


const getReservations = () => {
    ReservationService
        .listReservationsForEventAdmin(props.event.id)
        .then((response) => {
            reservations.value = response.data.data
        });
};

const onConfirm = () => {
    const promises = [];

    for (let email of uniqueEmails.value) {
        const reminderData = {
            event: props.event.id,
            scheduledAt: reminderDateTime.value,
            status: "pending",
            email: email,
        };

        promises.push(ReminderService.new(reminderData));
    }

    Promise.all(promises)
};


onMounted(() => {
    getReservations();
});
</script>

<template>
    <div class="modal fade" tabindex="-1" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content pb-1">
                <div class="modal-header">
                    <h4 class="modal-title me-3">Reminder email</h4>
                    <button @click="dismissModal" type="button" class="btn-close"></button>
                </div>

                <div class="modal-body">
                    <p class="text-muted mb-4">Numarul de mailuri: {{ uniqueEmails.length }}</p>
                    <div class="form-check mb-3 p-3 border rounded">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="reminderOption"
                            id="sendNow"
                            value="now"
                            v-model="reminderOption"
                        >
                        <label class="form-check-label ms-2" for="sendNow">
                            <strong>Trimite acum</strong>
                            <div class="text-muted small">Email-urile vor fi trimise imediat</div>
                        </label>
                    </div>

                    <div class="form-check mb-3 p-3 border rounded">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="reminderOption"
                            id="sendCustom"
                            value="custom"
                            v-model="reminderOption"
                        >
                        <label class="form-check-label ms-2" for="sendCustom">
                            <strong>Trimite inainte cu</strong>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <input
                                    type="number"
                                    min="1"
                                    max="999"
                                    class="form-control"
                                    style="max-width: 100px"
                                    v-model.number="customHours"
                                    :disabled="reminderOption !== 'custom'"
                                >
                                <div class="d-flex text-muted">ore</div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button @click="onConfirm" type="button" class="btn btn-primary">Trimite reminder</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
