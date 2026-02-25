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

    <div class="flex justify-between mb-4">
        <div>
            <input 
                type="search" 
                v-model="searchQuery"
                placeholder="Search By Department..."
                 class="bg-white rounded-md px-4 py-2 border border-gray-300"
            />
        </div>

        <div class="bg-green-500 hover:bg-green-600 rounded-md px-4 py-2">
            <Link href="/adddepartment" class="text-white">Add New Department</Link>
        </div>
    </div>

    <table class="w-full rounded-md shadow-lg overflow-hidden mb-6 table-auto bg-white">
        <thead class="border-b border-slate-100 text-slate-600 text-center">
            <tr class="bg-gray-400 text-black font-medium">
                <th class="px-6 py-4">ID No.</th>
                <th>Department</th>
                <th>Manager ID</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <tr 
                v-for="department in filteredDepartments" 
                :key="department.id"
                class="bg-white-100 w-full p-0 text-center border-b border-gray-200 hover:bg-gray-50"
            >
                <td class="px-6 py-4">{{ department.id }}</td>
                <td class="px-6 py-4">{{ department.name }}</td>
                <td class="px-6 py-4">{{ department.manager_id || 'N/A' }}</td>
                <td class="px-6 py-4 space-x-4">
                    <Link 
                        :href="`/editdepartment/${department.id}`" 
                        class="bg-yellow-300 rounded-sm px-4 py-1 shadow-md"
                    >
                        Edit
                    </Link>
                    <button 
                        @click="deleteDepartment(department.id)"
                        class="bg-red-500 text-white rounded-sm px-2 py-1 shadow-md"
                    >
                        Delete
                    </button>
                </td>
            </tr>

            <tr v-if="!departments || departments.length === 0">
                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                    No departments found
                </td>
            </tr>
        </tbody>
    </table>
</template>