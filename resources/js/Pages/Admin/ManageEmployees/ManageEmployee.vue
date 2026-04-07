<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, computed, watch, withDefaults } from 'vue'
import PaginationLinks from '../../Components/PaginationLinks.vue'

interface Employee {
    id: number
    first_name: string
    last_name: string
    department?: { name: string }
}

interface PaginatedEmployees {
    data: Employee[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    links: any[]
}

const props = withDefaults(defineProps<{
    employees: PaginatedEmployees
    filters?: { search?: string }
}>(), {
    filters: () => ({})
})

const search = ref(props.filters?.search ?? '')

let searchTimeout: ReturnType<typeof setTimeout> | null = null

const triggerFetch = () => {
    clearTimeout(searchTimeout ?? undefined)
    searchTimeout = setTimeout(() => {
        router.get(
            '/admin/manageemployees',
            {
                search: search.value || undefined,
                page: 1
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['employees', 'filters']
            }
        )
    }, 500)
}

watch(search, triggerFetch)

const employeeTableData = computed(() =>
    (props.employees?.data ?? []).map((employee: any) => ({
        id: employee.id,
        serial_no: employee.serial_no,
        first_name: employee.first_name,
        last_name: employee.last_name,
        department: employee.department?.name ?? 'N/A'
    }))
)

const tableColumns = [
    { label: 'Employee ID', key: 'id' },
    { label: 'Name', key: 'name' },
    { label: 'Department', key: 'department' },
    { label: 'Action', key: 'actions', align: 'center' }
]

// --- Delete confirmation modal state ---
const showDeleteModal = ref(false)
const employeeToDelete = ref<{ id: number; name: string } | null>(null)

const confirmDelete = (employee: { id: number; first_name: string; last_name: string }) => {
    employeeToDelete.value = {
        id: employee.id,
        name: `${employee.first_name} ${employee.last_name}`
    }
    showDeleteModal.value = true
}

const cancelDelete = () => {
    showDeleteModal.value = false
    employeeToDelete.value = null
}

const proceedDelete = () => {
    if (employeeToDelete.value) {
        router.delete(`/admin/delete/${employeeToDelete.value.id}`, {
            onFinish: () => {
                showDeleteModal.value = false
                employeeToDelete.value = null
            }
        })
    }
}
</script>

<template>
  <div class="flex flex-col px-6">

    <Head title=" | Employee Management" />

    <h1 class="text-4xl font-bold text-center mb-12">Employee Management</h1>

    <div class="flex flex-wrap justify-between mb-8 gap-4">
      <input v-model="search" type="text" placeholder="Search by EMP ID"
        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />

      <div class="flex flex-wrap justify-end gap-3">
        <a href="/admin/reports/employees" target="_blank"
          class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-md">
          Employee List PDF
        </a>
        <Link href="/admin/employee/create"
          class="bg-green-500 hover:bg-green-600 text-white font-semibold rounded-md px-4 py-2">
          Add Employee
        </Link>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
      <table class="min-w-full text-left whitespace-nowrap">
        <thead class="bg-gray-400">
          <tr>
            <th v-for="column in tableColumns" :key="column.key"
              :class="[column.align === 'center' ? 'text-center' : '']">
              {{ column.label }}
            </th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="employee in employeeTableData" :key="employee.id" class="border-t-4 border-gray-200">
            <td>{{ 'EMP-' + String(employee.id).padStart(3, '0') }}</td>
            <td>{{ employee.first_name }} {{ employee.last_name }}</td>
            <td>{{ employee.department }}</td>
            <td>
              <div class="flex justify-center gap-3">
                <Link :href="`/admin/view/${employee.id}`"
                  class="inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold transition bg-blue-500 hover:bg-blue-600 text-white">
                  View
                </Link>
                <Link :href="`/admin/edit/${employee.id}`"
                  class="inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold transition bg-yellow-400 hover:bg-yellow-500 text-white">
                  Edit
                </Link>
                <button
                  @click="confirmDelete(employee)"
                  class="inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold transition bg-red-500 hover:bg-red-600 text-white cursor-pointer">
                  Delete
                </button>
              </div>
            </td>
          </tr>

          <tr v-if="employeeTableData.length === 0">
            <td colspan="4" class="p-8 text-center text-gray-500 border-t-4 border-slate-200">
              No employees found
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-6">
      <PaginationLinks :paginator="employees" />
    </div>

    <!-- Delete Confirmation Modal -->
    <Teleport to="body">
      <div v-if="showDeleteModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">

          <!-- Warning Icon -->
          <div class="flex justify-center mb-5">
            <div class="bg-red-100 rounded-full p-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
              </svg>
            </div>
          </div>

          <h2 class="text-2xl font-bold text-center text-gray-900 mb-2">Delete Employee</h2>
          <p class="text-center text-gray-500 mb-1">Are you sure you want to delete</p>
          <p class="text-center font-semibold text-gray-800 text-lg mb-2">
            {{ employeeToDelete?.name }}
          </p>
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