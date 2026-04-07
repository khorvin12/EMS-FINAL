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

const props = withDefaults(defineProps<{
    departments: PaginatedDepartments
    filters?: { search?: string }
}>(), {
    filters: () => ({})
})

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

// --- Delete confirmation modal state ---
const showDeleteModal = ref(false)
const departmentToDelete = ref<{ id: number; name: string } | null>(null)

const confirmDelete = (dept: { id: number; name: string }) => {
    departmentToDelete.value = { id: dept.id, name: dept.name }
    showDeleteModal.value = true
}

const cancelDelete = () => {
    showDeleteModal.value = false
    departmentToDelete.value = null
}

const proceedDelete = () => {
    if (departmentToDelete.value) {
        router.delete(`/admin/departments/${departmentToDelete.value.id}`, {
            onFinish: () => {
                showDeleteModal.value = false
                departmentToDelete.value = null
            }
        })
    }
}
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
                            <Link :href="`/admin/editdepartment/${row.id}`"
                                class="bg-yellow-400 hover:bg-yellow-500 inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold transition">
                                Edit
                            </Link>
                            <button @click="confirmDelete(row)"
                                class="bg-red-500 hover:bg-red-600 inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold transition text-white cursor-pointer">
                                Delete
                            </button>
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

        <!-- Delete Confirmation Modal -->
        <Teleport to="body">
            <div v-if="showDeleteModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
                    <div class="flex justify-center mb-5">
                        <div class="bg-red-100 rounded-full p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-center text-gray-900 mb-2">Delete Department</h2>
                    <p class="text-center text-gray-500 mb-1">Are you sure you want to delete</p>
                    <p class="text-center font-semibold text-gray-800 text-lg mb-2">{{ departmentToDelete?.name }}</p>
                    <p class="text-center text-sm text-red-500 mb-8">
                        This action cannot be undone. All related records will be permanently removed.
                    </p>
                    <div class="flex gap-3">
                        <button @click="cancelDelete"
                            class="flex-1 px-6 py-3 rounded-xl border-2 border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button @click="proceedDelete"
                            class="flex-1 px-6 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold transition shadow-md hover:shadow-lg">
                            Yes, Delete
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>