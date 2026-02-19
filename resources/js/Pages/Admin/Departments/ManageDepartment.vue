<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PaginationLinks from '../../Components/PaginationLinks.vue';

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

const tableColumns = [
    { label: 'ID', key: 'id' },
    { label: 'Department', key: 'department' },
    { label: 'Manager ID', key: 'manager id' },
    { label: 'Action', key: 'actions', align: 'center' }
]

const actionButtons = [
    {
        label: 'Edit',
        href: (id) => `/departments/${id}/edit`,
        color: 'bg-yellow-400 hover:bg-yellow-500'
    },
    {
        label: 'Delete',
        href: (id) => `/departments/${id}`,
        color: 'bg-red-500 hover:bg-red-600',
        method: 'delete',
        as: 'button'
    }
]
</script>

<template>
    <div class="flex flex-col px-6">

        <!-- Title -->
        <h1 class="text-center text-3xl font-bold mb-12">Manage Departments</h1>

        <!-- Search + Add Button -->
        <div class="flex justify-between mb-6">
            <input type="search" v-model="searchQuery" placeholder="Search by Name or ID"
                class="bg-white border border-gray-300 rounded-md p-2 focus:outline-none focus:border-blue-400" />


            <Link href="/adddepartment"
                class="text-black font-semibold bg-green-500 hover:bg-green-600 rounded-md px-4 py-2">Add New
                Department</Link>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-gray-400 text-black font-medium">
                    <tr>
                        <th v-for="column in tableColumns" :key="column.key" :class="[
                            'p-6',
                            column.align === 'center' ? 'text-center' : ''
                        ]">
                            {{ column.label }}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="department in departments.data" :key="department.id"
                        class="border-t-4 border-slate-200">
                        <td class="px-6 py-4">{{ department.id }}</td>
                        <td class="px-6 py-4">{{ department.name }}</td>
                        <td class="px-6 py-4">{{ department.manager_id || 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <Link v-for="action in actionButtons" :key="action.label"
                                    :href="action.href(department.id)" :method="action.method" :as="action.as"
                                    :class="[action.color, 'px-4 py-2 rounded-md inline-block']">
                                    {{ action.label }}
                                </Link>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="!departments || departments.length === 0">
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            No departments found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <PaginationLinks :paginator="departments" />
        </div>
    </div>
</template>