<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    department: Object
});

const form = useForm({
    name: props.department.name,
    description: props.department.description || '',
    manager_id: props.department.manager_id || ''
});

const updateDepartment = () => {
    form.put(`/editdepartment/${props.department.id}`, {
        onSuccess: () => {}
    });
};
</script>

<template>
    <div class="flex items-center justify-center py-24">

        <Head title=" | Edit Department" />

        <div class="bg-white w-full max-w-sm px-6 py-4 rounded-md shadow-md border-4 border-yellow-300">

            <div class="flex justify-end">
                <Link href="/departments" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    ← Back
                </Link>
            </div>

            <h1 class="text-xl font-bold mb-8 text-center">Edit Department</h1>

            <form @submit.prevent="updateDepartment">
                <div class="grid grid-cols-1 mb-6 space-y-1">
                    <label for="departmentName">Department Name</label>
                    <input type="text" v-model="form.name" id="departmentName" placeholder="Department Name"
                        class="p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                        required />
                    <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
                </div>

                <div class="grid grid-cols-1 mb-6 space-y-1">
                    <label for="description">Description</label>
                    <textarea v-model="form.description" rows="5" id="description" placeholder="Description"
                        class="p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"></textarea>
                    <span v-if="form.errors.description" class="text-red-500 text-sm">{{ form.errors.description }}</span>
                </div>

                <div class="grid grid-cols-1 mb-6 space-y-1">
                    <label for="managerId">Manager ID</label>
                    <input type="text" v-model="form.manager_id" id="managerId" placeholder="Manager ID (Optional)"
                        class="p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400" />
                    <span v-if="form.errors.manager_id" class="text-red-500 text-sm">{{ form.errors.manager_id }}</span>
                </div>

                <div class="mt-6 font-bold text-center">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 w-24 py-2 rounded-md text-sm font-semibold transition disabled:opacity-50"
                        :disabled="form.processing">
                        {{ form.processing ? 'Updating...' : 'Update' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>