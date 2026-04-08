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

// ✅ Confirm modal state
const showConfirmModal = ref(false)
const departmentToDelete = ref<number | null>(null)

const confirmDelete = (id: number) => {
    departmentToDelete.value = id
    showConfirmModal.value = true
}

const handleDelete = () => {
    if (departmentToDelete.value !== null) {
        router.delete(`/admin/departments/${departmentToDelete.value}`)
    }
    showConfirmModal.value = false
    departmentToDelete.value = null
}

const cancelDelete = () => {
    showConfirmModal.value = false
    departmentToDelete.value = null
}

let searchTimeout: ReturnType<typeof setTimeout> | null = null

const triggerFetch = (isSearch = false) => {
    clearTimeout(searchTimeout ?? undefined)
    searchTimeout = setTimeout(() => {
        router.get(
            '/admin/departments',
            { search: searchQuery.value || undefined, page: 1 },
            { preserveState: true, preserveScroll: true, replace: true, only: ['departments', 'filters'] }
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
                        <td>{{ row.serial_no }}</td>
                        <td>{{ row.name }}</td>
                        <td>
                            {{ row.manager_id === 'N/A' ? 'N/A' : 'EMP-' + String(row.manager_id).padStart(3, '0') }}
                        </td>
                        <td class="flex justify-center gap-3">
                            <!-- Edit -->
                            <Link :href="`/admin/editdepartment/${row.id}`"
                                class="bg-yellow-400 hover:bg-yellow-500 inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold text-white transition">
                                Edit
                            </Link>
                            <!--  Delete triggers modal -->
                            <button @click="confirmDelete(row.id)"
                                class="bg-red-500 hover:bg-red-600 inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold text-white transition">
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

        <!--  Confirmation Modal -->
        <Teleport to="body">
            <div v-if="showConfirmModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                <div class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-sm mx-4 text-center">


                    <h2 class="text-xl font-bold text-gray-800 mb-2">Delete Department</h2>
                    <p class="text-gray-500 mb-6">
                        Are you sure you want to delete this department? <br>
                        <span class="text-red-500 font-medium">This action cannot be undone.</span>
                    </p>

                    <div class="flex gap-3 justify-center">
                        <button @click="cancelDelete"
                            class="px-6 py-2 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-100 transition">
                            Cancel
                        </button>
                        <button @click="handleDelete"
                            class="px-6 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white font-semibold transition">
                            Yes, Delete
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>