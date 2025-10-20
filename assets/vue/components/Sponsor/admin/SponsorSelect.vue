<script setup>

import {onMounted, ref, watch} from "vue";
import SponsorService from "../../../services/SponsorService";


const selectedSponsors = defineModel('selectedSponsors', {
    type: Array,
    required: true,
});

const props = defineProps({
    isDisabled: {
        type: Boolean,
        required: false,
        default: false,
    },
    event: {
      type: Object,
      required: true
    }
});

const baseUrl = window.location.origin;

const sponsors = ref([]);

const getSponsors = () => {
    SponsorService
        .listAdmin()
        .then((response) => {
            sponsors.value = response.data.data;
        })
}

const selectedSponsorIds = ref(selectedSponsors.value?.map(s => s.id));

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text)
        .then(() => {
            alert("URL copied to clipboard!");
        })
}

watch(selectedSponsorIds, () => {
    const newSelectedSponsors = [];

    for (let id of selectedSponsorIds.value) {
        const sponsor = sponsors.value.find(obj => obj.id === id);
        newSelectedSponsors.push(sponsor);
    }

    selectedSponsors.value = newSelectedSponsors;
})

onMounted(() => {
    getSponsors()
})

const iconClass = 'position-absolute top-50 start-0 translate-middle-y ms-3 fs-5';

const formPs = 2.7 //rem

</script>

<template>
    <div class="position-relative mt-4">
        <div v-if="!isDisabled">
            <select
                v-model="selectedSponsorIds"
                class="form-select"
                data-placeholder="Choose anything"
                multiple
                :style="`padding-left: ${formPs}rem`"
                :disabled="isDisabled"
            >
                <option disabled value="">Selectează sponsorul/sponsorii</option>
                <option v-for="sponsor in sponsors" :key="sponsor.id" :value="sponsor.id">
                    {{ sponsor.name }}
                </option>
            </select>

            <i class="bi bi-person-fill-add" :class="iconClass"></i>
        </div>

        <div v-else class="mt-2">
            <strong>Sponsorii evenimentului:</strong>
            <div class="accordion mt-2" id="sponsorAccordion">
                <div v-for="(sponsor, index) in selectedSponsors" :key="sponsor.id" class="accordion-item">
                    <h2 class="accordion-header" :id="'heading' + sponsor.id">
                        <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            :data-bs-target="'#collapse' + sponsor.id"
                            aria-expanded="false"
                            :aria-controls="'collapse' + sponsor.id"
                        >
                            {{ sponsor.name }}
                        </button>
                    </h2>
                    <div
                        :id="'collapse' + sponsor.id"
                        class="accordion-collapse collapse"
                        :aria-labelledby="'heading' + sponsor.id"
                        data-bs-parent="#sponsorAccordion"
                    >
                        <div class="accordion-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item mb-1">
                                    <strong>Link:</strong>
                                    <div class="d-flex align-items-center">
                                        <input type="text" :value="`${baseUrl}/event/${props.event.id}/room/${props.event.room.id}?uuid=${sponsor.uuid}`" readonly class="form-control form-control-sm me-2">
                                        <i @click="copyToClipboard(`${baseUrl}/event/${props.event.id}/room/${props.event.room.id}?uuid=${sponsor.uuid}`)" class="bi bi-clipboard" style="cursor: pointer;"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>


<style scoped>

</style>
