<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    start_date: '',
    end_date: '',
    reason: '',
    description: ''
});

const submit = () => {
    form.post('/employee/leaves', {
        onSuccess: () => {
            // Redirect will be handled by controller
        },
        onError: () => {
            alert('Please check the form for errors');
        }
    });
};

const cancel = () => {
    form.reset();
    window.history.back();
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-lg px-6 py-4 rounded-md shadow-md border-4 border-green-600">
            <h1 class="text-xl font-bold mb-6 text-center">Request for Leave</h1>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-2 mb-6 gap-4">
                    <div>
                        <label class="block mb-1">From</label>
                        <input
                            type="date"
                            v-model="form.start_date"
                            class="w-full p-2 rounded-md border border-gray-300"
                            required
                        />
                        <span v-if="form.errors.start_date" class="text-red-500 text-sm">{{ form.errors.start_date }}</span>
                    </div>

                    <div>
                        <label class="block mb-1">To</label>
                        <input
                            type="date"
                            v-model="form.end_date"
                            class="w-full p-2 rounded-md border border-gray-300"
                            required
                        />
                        <span v-if="form.errors.end_date" class="text-red-500 text-sm">{{ form.errors.end_date }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-1">
                    <label>Reason</label>
                    <input
                        type="text"
                        v-model="form.reason"
                        placeholder="Title"
                        class="p-2 rounded-md border border-gray-300"
                        required
                    />
                    <span v-if="form.errors.reason" class="text-red-500 text-sm">{{ form.errors.reason }}</span>
                    
                    <textarea
                        rows="6"
                        v-model="form.description"
                        placeholder="Description"
                        class="p-2 rounded-md border border-gray-300"
                    />
                </div>

                <div class="mt-6 text-white font-bold text-center space-x-4">
                    <button
                        type="button"
                        @click="cancel"
                        class="bg-gray-600 hover:bg-gray-500 px-8 py-2 rounded-md"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-green-600 hover:bg-green-500 px-8 py-2 rounded-md disabled:opacity-50"
                    >
                        {{ form.processing ? 'Submitting...' : 'Submit Leave' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>