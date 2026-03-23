<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    employees: Array
});

const form = useForm({
    name:        '',
    description: '',
    manager_id:  ''
});

const submitDepartment = () => {
    form.post('/adddepartment', {
        onSuccess: () => { form.reset(); }
    });
};
</script>

<template>
    <div class="flex items-center justify-center py-24">

        <Head title=" | Add Department" />

        <div class="bg-white w-full max-w-sm px-6 py-4 rounded-md shadow-md border-4 border-green-600">

            <h1 class="text-xl font-bold mb-6 text-center">Add New Department</h1>

            <form @submit.prevent="submitDepartment">

                <div class="grid grid-cols-1 mb-6 space-y-1">
                    <label>Department Name</label>
                    <input type="text" v-model="form.name" placeholder="Department Name"
                        class="p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required />
                    <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
                </div>

                <div class="grid grid-cols-1 mb-6 space-y-1">
                    <label>Description</label>
                    <textarea v-model="form.description" rows="3" placeholder="Description"
                        class="p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                    <span v-if="form.errors.description" class="text-red-500 text-sm">{{ form.errors.description }}</span>
                </div>

                <div class="grid grid-cols-1 mb-6 space-y-1">
                    <label>Department Manager <span class="text-gray-400 text-xs">(optional)</span></label>
                    <select v-model="form.manager_id"
                        class="p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">-- Select Manager --</option>
                        <option v-for="emp in employees" :key="emp.id" :value="emp.id">
                            {{ emp.name }}
                        </option>
                    </select>
                    <span v-if="form.errors.manager_id" class="text-red-500 text-sm">{{ form.errors.manager_id }}</span>
                </div>

                <div class="mt-6 font-bold text-center">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 px-4 py-2 rounded-md text-sm font-semibold transition disabled:opacity-50"
                        :disabled="form.processing">
                        {{ form.processing ? 'Adding...' : 'Add Department' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>