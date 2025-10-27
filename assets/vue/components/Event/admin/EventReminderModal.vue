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

const reminders = ref([]);

const dismissModal = () => {
    props.instance.value.close(undefined);
}

const uniqueEmails = computed(() => {
    const emails = reservations.value.map(r => r.email);

    return [...new Set(emails)];
})

const reminderDateTime = computed(() => {
    if (reminderOption.value === 'now') {
        return new Date();
    }

    if (reminderOption.value === 'custom' && customHours.value) {
        const [year, month, day, hour, minute] = props.event.date
            .match(/\d+/g)
            .map(Number);
        const eventDate = new Date(year, month - 1, day, hour, minute);
        eventDate.setHours(eventDate.getHours() - customHours.value);
        return eventDate;
    }
});



const getReservations = () => {
    ReservationService
        .listReservationsForEventAdmin(props.event.id)
        .then((response) => {
            reservations.value = response.data.data
        });
};

const getReminders = () => {
    ReminderService
        .listReminders(props.event)
        .then((response) => {
            reminders.value = response.data.data
        });
};

const groupedReminders = computed(() => {
    const groups = {};
    reminders.value.forEach(r => {
        const key = r.scheduledAt;
        if (!groups[key]) groups[key] = [];
        groups[key].push(r);
    });
    return groups;
});

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

    getReminders();
};

const deleteBatch = (scheduledAt) => {
    const batch = reminders.value.filter(r => r.scheduledAt === scheduledAt);

    const promises = batch.map(r => ReminderService.deleteAdmin(r));

    Promise.all(promises)
        .then(() => {
            reminders.value = reminders.value.filter(r => r.scheduledAt !== scheduledAt);
        })
};


onMounted(() => {
    getReservations();
    getReminders();
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
                        <input class="form-check-input" type="radio" id="sendNow" value="now" v-model="reminderOption">
                        <label class="form-check-label ms-2" for="sendNow">
                            <strong>Trimite acum</strong>
                            <div class="text-muted small">Email-urile vor fi trimise imediat</div>
                        </label>
                    </div>

                    <div class="form-check mb-4 p-3 border rounded">
                        <input class="form-check-input" type="radio" id="sendCustom" value="custom" v-model="reminderOption">
                        <label class="form-check-label ms-2" for="sendCustom">
                            <strong>Trimite înainte cu</strong>
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

                    <div
                        class="accordion-container mt-3 border rounded"
                        style="max-height: 250px; overflow-y: auto;"
                    >
                        <div class="accordion" id="remindersAccordion">
                            <div
                                v-for="(batch, scheduledAt) in groupedReminders"
                                :key="scheduledAt"
                                class="accordion-item mb-2"
                            >
                                <h2 class="accordion-header d-flex justify-content-between align-items-center" :id="`heading-${scheduledAt}`">
                                    <button
                                        class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        :data-bs-target="`#collapse-${scheduledAt}`"
                                        aria-expanded="false"
                                    >
                                        {{ new Date(scheduledAt + 'Z').toLocaleString('ro-RO', { timeZone: 'Europe/Bucharest' }) }}
                                        <span class="badge bg-secondary ms-2">{{ batch.length }} emailuri</span>
                                    </button>

                                    <button
                                        @click.stop="deleteBatch(scheduledAt)"
                                        class="btn btn-sm btn-outline-danger me-2"
                                        title="Șterge toate reminder-ele"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </h2>

                                <div
                                    :id="`collapse-${scheduledAt}`"
                                    class="accordion-collapse collapse"
                                    :aria-labelledby="`heading-${scheduledAt}`"
                                    data-bs-parent="#remindersAccordion"
                                >
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            <li
                                                v-for="r in batch"
                                                :key="r.id"
                                                class="list-group-item d-flex justify-content-between align-items-center"
                                            >
                                                <span>{{ r.email }}</span>
                                                <span class="badge bg-info text-dark">{{ r.status }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button @click="onConfirm" type="button" class="btn btn-primary">Trimite reminder</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
