<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    departments: Array
});

const searchQuery = ref('');

const filteredDepartments = computed(() => {
    if (!searchQuery.value) return props.departments;

    return props.departments.filter(dept =>
        dept.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});


</script>

<template>
    <h1 class="text-center text-3xl font-bold mb-12">Manage Departments</h1>

    <div class="flex justify-between mb-6">

        <input type="search" v-model="searchQuery" placeholder="Search By Employee ID" class="bg-white p-2 border border-gray-300 rounded-md
           focus:outline-none focus:border-blue-400" />

        <div class="bg-green-600 hover:bg-green-500 rounded-md px-4 py-2">
            <Link href="/adddepartment" class="text-black">Add New Department</Link>
        </div>
    </div>

    <table class="w-full rounded-lg shadow-lg overflow-hidden table-fixed bg-white">
        <thead class="bg-gray-400 text-black font-medium">
            <tr>
                <th class="py-6">ID NO</th>
                <th>Department</th>
                <th>Manager ID</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody class="bg-white-100 text-center border-slate-200 border-t-4">
            <tr v-for="department in filteredDepartments" :key="department.id">
                <td class="py-4">{{ department.id }}</td>
                <td class="py-4">{{ department.name }}</td>
                <td class="py-4">{{ department.manager_id || 'N/A' }}</td>
                <td class="py-4 space-x-4">
                    <Link :href="`/editdepartment/${department.id}`" class="bg-yellow-300 rounded-sm px-4 py-1.5">
                        Edit
                    </Link>
                    <button @click="deleteDepartment(department.id)" class="bg-red-500 text-white rounded-sm px-2 py-1">
                        Delete
                    </button>
                </td>
            </tr>

            <tr v-if="!departments || departments.length === 0">
                <td colspan="4" class="py-6 text-center text-gray-500">
                    No departments found
                </td>
            </tr>
        </tbody>
    </table>
</template>