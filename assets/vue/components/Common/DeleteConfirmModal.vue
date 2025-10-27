<script setup>

const props = defineProps({
    instance: {
        type: Object,
        required: true,
    },
});

const dismissModal = () => {
    props.instance.value.close(undefined);
}

const confirmDelete = () => {
    props.instance.value.close(true);
}

</script>

<template>
    <div class="modal fade" tabindex="-1" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content pb-1">
                <div class="modal-header">
                    <h4 class="modal-title me-3">Confirmare ștergere</h4>
                    <button @click="dismissModal" type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">Sigur dorești să ștergi această înregistrare?</div>

                    <div class="d-flex-center gap-2">
                        <button @click="dismissModal" class="btn btn-outline-secondary rounded-4">Nu</button>
                        <button @click="confirmDelete" class="btn btn-outline-danger rounded-4">Da</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
