<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PaginationLinks from '../../Components/PaginationLinks.vue';

const props = defineProps({
    departments: Object
});

const searchQuery = ref('');

const filteredDepartments = computed(() => {
    const data = props.departments.data || [];

    if (!searchQuery.value) return data;
    return data.filter(dept =>
        dept.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        dept.id.toString().includes(searchQuery.value) ||
        dept.manager_id?.toString().includes(searchQuery.value)
    );
});

const tableColumns = [
    { label: 'Serial No', key: 'id' },
    { label: 'Department', key: 'department' },
    { label: 'Manager ID', key: 'manager id' },
    { label: 'Action', key: 'actions', align: 'center' }
]

const DepartmentDataTable = computed(() => {
    return filteredDepartments.value.map(dept => ({
        id: dept.id,
        name: dept.name,
        manager_id: dept.manager_id || 'N/A'
    }));
})

const actionButtons = [
    {
        label: 'Edit',
        href: (id) => `/editdepartment/${id}`,
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

        <h1 class="text-center text-4xl font-bold mb-12">Manage Departments</h1>

        <div class="flex justify-between mb-6 gap-4">
            <input v-model="searchQuery" type="text" placeholder="Search"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />

            <Link href="/adddepartment" class="font-semibold bg-green-500 hover:bg-green-600 rounded-md px-4 py-2 transition whitespace-nowrap">
                Add Department
            </Link>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-gray-400 text-black font-medium">
                    <tr>
                        <th v-for="column in tableColumns" :key="column.key"
                            :class="[column.align === 'center' ? 'text-center' : '']">
                            {{ column.label }}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="row in DepartmentDataTable" :key="row.id" class="border-t-4 border-slate-200">
                        <td>
                            {{ row.id }}
                        </td>
                        <td>
                            {{ row.name }}
                        </td>
                        <td>
                            {{ row.manager_id }}
                        </td>
                        <td class="flex justify-center gap-3">
                            <Link v-for="action in actionButtons" :key="action.label" :href="action.href(row.id)"
                                :method="action.method" :as="action.as"
                                :class="[action.color, 'inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold transition']">
                                {{ action.label }}
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="filteredDepartments.length === 0">
                        <td colspan="4" class="p-8 text-center text-gray-500 border-t-4 border-slate-200">
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