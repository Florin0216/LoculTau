<script setup>

const value = defineModel('value', {
    type: Object,
    required: true,
})

const props = defineProps({
    seatDetails: {
        type: Array,
        required: true,
    },
    showSeatDetails: {
        type: Boolean,
        default: true
    }
});

</script>

<template>
    <div class="bg-gray-800 rounded-2xl p-6 w-full max-w-lg shadow-lg mx-auto mt-6">
        <div v-if="showSeatDetails" v-for="seat in seatDetails" class="text-white mb-5 text-center">
            <template v-if="seat?.section && seat.section.toLowerCase() !== 'main'">
                {{ seat.section }} — Loc {{ seat.number }}
            </template>
            <template v-else>
                Rândul {{ String.fromCharCode(65 + (seat?.rowNo - 1) + (seat?.rowNo >= 17 ? 1 : 0)) }} — Loc {{ seat?.number }}
            </template>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium mb-1 text-white">Nume complet</label>
            <input
                type="text"
                v-model="value.name"
                class="w-full px-3 py-2 rounded-md bg-gray-700 border border-gray-600 text-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nume complet"
                required
            />
        </div>

        <div>
            <label class="block text-sm font-medium mb-1 text-white">Email</label>
            <input
                type="email"
                v-model="value.email"
                class="w-full px-3 py-2 rounded-md bg-gray-700 border border-gray-600 text-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Email"
                required
            />
        </div>
    </div>
</template>
