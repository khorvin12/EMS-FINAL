<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import PaginationLinks from '../../Components/PaginationLinks.vue'

interface Department {
    id: number
    name: string
    manager_id: number | null
}

interface PaginatedDepartments {
    data: Department[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    links: any[]
}

const props = defineProps<{
    departments: PaginatedDepartments
    filters: { search?: string }
}>()

const searchQuery = ref(props.filters?.search ?? '')

let searchTimeout: ReturnType<typeof setTimeout> | null = null

const triggerFetch = (isSearch = false) => {
    clearTimeout(searchTimeout ?? undefined)
    searchTimeout = setTimeout(() => {
        router.get(
            '/admin/departments',
            {
                search: searchQuery.value || undefined,
                page: 1
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['departments', 'filters']
            }
        )
    }, isSearch ? 500 : 0)
}

watch(searchQuery, () => triggerFetch(true))

const tableColumns = [
    { label: 'Serial No', key: 'id' },
    { label: 'Department', key: 'department' },
    { label: 'Manager ID', key: 'manager_id' },
    { label: 'Action', key: 'actions', align: 'center' }
]

const DepartmentDataTable = computed(() => {
    return (props.departments?.data ?? []).map((dept: any) => ({
        id: dept.id,
        serial_no: dept.serial_no,
        name: dept.name,
        manager_id: dept.manager_id || 'N/A'
    }))
})

const actionButtons = [
    {
        label: 'Edit',
        href: (id: number) => `/admin/editdepartment/${id}`,
        color: 'bg-yellow-400 hover:bg-yellow-500',
        method: undefined,
        as: undefined
    },
    {
        label: 'Delete',
        href: (id: number) => `/admin/departments/${id}`,
        color: 'bg-red-500 hover:bg-red-600',
        method: 'delete' as const,
        as: 'button' as const
    }
]
</script>

<template>
    <div class="flex flex-col px-6">

        <Head title=" | Department Management" />

        <h1 class="text-center text-4xl font-bold mb-12">Department Management</h1>

        <div class="flex justify-between mb-8 gap-4">
            <input v-model="searchQuery" type="text" placeholder="Search by Serial No"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />

            <Link href="/admin/adddepartment"
                class="font-semibold bg-green-500 hover:bg-green-600 text-white rounded-md px-4 py-2 transition whitespace-nowrap">
                Add Department
            </Link>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full text-left transition whitespace-nowrap">
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
                            {{ row.serial_no }}
                        </td>
                        <td>
                            {{ row.name }}
                        </td>
                        <td>
                            {{ row.manager_id === 'N/A' ? 'N/A' : 'EMP-' + String(row.manager_id).padStart(3, '0') }}
                        </td>
                        <td class="flex justify-center gap-3">
                            <Link v-for="action in actionButtons" :key="action.label" :href="action.href(row.id)"
                                :method="action.method" :as="action.as"
                                :class="[action.color, 'inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold transition']">
                                {{ action.label }}
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="DepartmentDataTable.length === 0">
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