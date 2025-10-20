<script setup>

import EditingButtonGroup from "../../Common/EditingButtonGroup.vue";
import {computed, onMounted, ref} from "vue";
import {getClone} from "../../../helpers/getClone";
import {isValue} from "../../../helpers/isValue";
import SponsorCreateDto from "../../../dto/Sponsor/SponsorCreateDto";
import SponsorEditDto from "../../../dto/Sponsor/SponsorEditDto";
import SponsorService from "../../../services/SponsorService";

const props = defineProps({
    instance: {
        type: Object,
        required: true,
    },
    sponsor: {
        type: Object,
        required: true,
    },
    isEditing: {
        type: Boolean,
        required: false,
        default: false,
    }
});

const sponsor = ref(getClone(props.sponsor));

let eventInitData = getClone(sponsor.value);

const isEditing = ref(props.isEditing);

const isNewEvent = computed(() => {
    return !isValue(sponsor.value.id);
});

const onEditCancel = () => {
    sponsor.value = getClone(eventInitData);
}

const onEditConfirm = () => {
    const promise = isNewEvent.value
        ? SponsorService.newAdmin(new SponsorCreateDto(sponsor.value))
        : SponsorService.editAdmin(sponsor.value.id, new SponsorEditDto(sponsor.value));

    promise
        .then((response) => {
            sponsor.value = response.data.data;

            isEditing.value = false;

            eventInitData = getClone(sponsor.value);
        })
        .catch(err => console.error(err));
}

const dismissModal = () => {
    props.instance.value.close(undefined);
}

const formPs = 2.7;

const iconClass = 'position-absolute top-50 start-0 translate-middle-y ms-3 fs-5';

onMounted(() => {

})

</script>

<template>
    <div class="modal fade" tabindex="-1" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content pb-1">
                <div class="modal-header">
                    <h4 class="modal-title me-3">
                        <template v-if="isNewEvent">Adaugă sponsor</template>
                        <template v-else>Detalii sponsor</template>
                    </h4>
                    <editing-button-group
                        v-model:is-editing="isEditing"
                        :font-size="5"
                        @confirm="onEditConfirm"
                        @cancel="onEditCancel"
                    ></editing-button-group>
                    <button @click="dismissModal" type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="position-relative mb-4">
                        <input v-model="sponsor.name" :disabled="!isEditing" type="text" class="form-control "
                               id="loginEmailInput" placeholder="Nume" :style="`padding-left: ${formPs}rem`">

                        <i class="bi bi-card-text" :class="iconClass"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
