<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import PaginationLinks from '../../Components/PaginationLinks.vue'

const props = defineProps({
  employees: {
    type: Object,
    required: true
  }
})

const search = ref('')

const filteredEmployees = computed(() => {
  if (!search.value) return props.employees.data

  const searchLower = search.value.toLowerCase()
  return props.employees.data.filter(employee =>
    employee.name.toLowerCase().includes(searchLower) ||
    employee.id.toString().includes(search.value)
  )
})

const tableColumns = [
  { label: 'Employee ID', key: 'id' },
  { label: 'Name', key: 'name' },
  { label: 'Department', key: 'department' },
  { label: 'Action', key: 'actions', align: 'center' }
]

const actionButtons = [
  {
    label: 'View',
    href: (id) => `/view/${id}`,
    color: 'bg-blue-500 hover:bg-blue-600'
  },
  {
    label: 'Edit',
    href: (id) => `/edit/${id}`,
    color: 'bg-yellow-400 hover:bg-yellow-500'
  },
  {
    label: 'Delete',
    href: (id) => `/delete/${id}`,
    color: 'bg-red-500 hover:bg-red-600',
    method: 'delete',
    as: 'button'
  }
]

</script>

<template>
  <div class="flex flex-col px-6">

    <!-- Title -->
    <h1 class="text-3xl font-bold text-center mb-12">Manage Employees</h1>

    <!-- Search + Add Button -->
    <div class="flex justify-between items-center mb-6 gap-4 flex-wrap">
      <input v-model="search" type="text" placeholder="Search by Name or ID"
        class="bg-white p-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-400" />

      <Link href="/addnewemployee"
        class="bg-green-500 hover:bg-green-600 text-black font-semibold px-4 py-2 rounded-md">
        Add New Employee
      </Link>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
      <table class="min-w-full text-left">
        <thead class="bg-gray-400 text-black font-bold">
          <tr>
            <th v-for="column in tableColumns" :key="column.key" :class="[
              column.align === 'center' ? 'text-center' : ''
            ]">
              {{ column.label }}
            </th>
          </tr>
        </thead>

        <tbody>
          <!-- Employee Rows -->
          <tr v-for="employee in filteredEmployees" :key="employee.id" class="border-t-4 border-gray-200">

            <td>
              {{ employee.id }}
            </td>

            <td>
              {{ employee.name }}
            </td>

            <td>
              {{ employee.department?.name ?? 'N/A' }}
            </td>

            <td class="flex justify-center gap-2">
              <Link v-for="action in actionButtons" :key="action.label" :href="action.href(employee.id)"
                :method="action.method" :as="action.as"
                :class="[action.color, 'px-4 py-2 rounded-md inline-block text-sm font-semibold']">
                {{ action.label }}
              </Link>
            </td>

          </tr>

          <!-- Empty State -->
          <tr v-if="filteredEmployees.length === 0">
            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
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