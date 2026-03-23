<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
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

const props = defineProps<{
    employees: PaginatedEmployees
    filters: { search?: string }
}>()

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
    { label: 'Serial No', key: 'serial_no' },
    { label: 'Employee ID', key: 'id' },
    { label: 'Name', key: 'name' },
    { label: 'Department', key: 'department' },
    { label: 'Action', key: 'actions', align: 'center' }
]

const actionButtons = [
    { label: 'View', href: (id: number) => `/admin/view/${id}`, color: 'bg-blue-500 hover:bg-blue-600', method: undefined, as: undefined },
    { label: 'Edit', href: (id: number) => `/admin/edit/${id}`, color: 'bg-yellow-400 hover:bg-yellow-500', method: undefined, as: undefined },
    { label: 'Delete', href: (id: number) => `/admin/delete/${id}`, color: 'bg-red-500 hover:bg-red-600', method: 'delete' as const, as: 'button' as const }
]
</script>

<template>
  <div class="flex flex-col px-6">

    <Head title=" | Employee Management" />

    <h1 class="text-4xl font-bold text-center mb-12">Employee Management</h1>

    <div class="flex flex-wrap justify-between mb-8 gap-4">
      <input v-model="search" type="text" placeholder="Search by EMP ID"
        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />

      <div class="flex flex-wrap justify-end gap-3">
        <a href="/admin/reports/employee" target="_blank"
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
                <Link v-for="action in actionButtons" :key="action.label" :href="action.href(employee.id)"
                  :method="(action.method as any)" :as="(action.as as any)"
                  :class="[action.color, 'inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold transition']">
                  {{ action.label }}
                </Link>
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
  </div>
</template>