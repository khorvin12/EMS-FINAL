<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
    manager_id: ''
});

const submitDepartment = () => {
    form.post('/adddepartment', {
        onSuccess: () => {
            form.reset();
        }
    });
};
</script>

<template>
    <div class="flex items-center justify-center h-full">
        <div class="bg-white w-full max-w-sm px-6 py-4 rounded-md shadow-md border-5 border-green-600">
            <h1 class="text-xl font-bold mb-6 text-center">Add New Department</h1>

            <form @submit.prevent="submitDepartment">
                <div class="grid grid-cols-1 mb-6 space-y-1">
                    <label for="departmentName">Department Name</label>
                    <input 
                        type="text" 
                        v-model="form.name"
                        id="departmentName" 
                        placeholder="Department Name"
                        class="p-2 rounded-md border border-gray-300"
                        required
                    />
                    <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
                </div>

                <div class="grid grid-cols-1 mb-6 space-y-1">
                    <label for="description">Description</label>
                    <textarea 
                        v-model="form.description"
                        rows="5" 
                        id="description" 
                        placeholder="Description"
                        class="p-2 rounded-md border border-gray-300"
                    ></textarea>
                    <span v-if="form.errors.description" class="text-red-500 text-sm">{{ form.errors.description }}</span>
                </div>

                <div class="grid grid-cols-1 mb-6 space-y-1">
                    <label for="managerId">Manager ID</label>
                    <input 
                        type="text" 
                        v-model="form.manager_id"
                        id="managerId" 
                        placeholder="Manager ID (Optional)"
                        class="p-2 rounded-md border border-gray-300"
                    />
                    <span v-if="form.errors.manager_id" class="text-red-500 text-sm">{{ form.errors.manager_id }}</span>
                </div>

                <div class="mt-6 text-white font-bold text-center">
                    <button 
                        type="submit"
                        class="bg-green-500 hover:bg-green-600 px-8 py-2 rounded-md disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Adding...' : 'Add Department' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>