<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    start_date: '',
    end_date:   '',
    type:       '',
    reason:     ''
});

const submit = () => { form.post('/employee/leaves'); };
const cancel = () => { form.reset(); window.history.back(); };
</script>

<template>
    <div class="flex items-center justify-center px-4 py-24">

        <Head title=" | Create Leave" />

        <div class="bg-white w-full max-w-lg px-4 py-3 rounded-lg shadow-md border-4 border-green-600">

            <h1 class="text-xl font-bold mb-4 text-center">Request for Leave</h1>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 sm:grid-cols-2 mb-4 gap-3">
                    <div>
                        <label class="block mb-1 text-sm">From</label>
                        <input type="date" v-model="form.start_date"
                            class="w-full p-2 text-sm rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            required />
                        <span v-if="form.errors.start_date" class="text-red-500 text-xs">{{ form.errors.start_date }}</span>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm">To</label>
                        <input type="date" v-model="form.end_date"
                            class="w-full p-2 text-sm rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            required />
                        <span v-if="form.errors.end_date" class="text-red-500 text-xs">{{ form.errors.end_date }}</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm">Type</label>
                    <select v-model="form.type"
                        class="w-full p-2 text-sm rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        <option value="">Select Type</option>
                        <option value="sick">Sick</option>
                        <option value="vacation">Vacation</option>
                        <option value="emergency">Emergency</option>
                        <option value="personal">Personal</option>
                    </select>
                    <span v-if="form.errors.type" class="text-red-500 text-xs">{{ form.errors.type }}</span>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm">Reason</label>
                    <textarea rows="3" v-model="form.reason" placeholder="Reason"
                        class="w-full p-2 text-sm rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    <span v-if="form.errors.reason" class="text-red-500 text-xs">{{ form.errors.reason }}</span>
                </div>

                <div class="flex justify-center gap-4">
                    <button type="button" @click="cancel"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm">
                        Cancel
                    </button>
                    <button type="submit" :disabled="form.processing"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-md text-sm disabled:opacity-50">
                        {{ form.processing ? 'Submitting...' : 'Submit Leave' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>