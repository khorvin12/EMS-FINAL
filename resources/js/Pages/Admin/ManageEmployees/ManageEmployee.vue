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
  return props.employees.data.filter(employee => {
    const fullName = (employee.first_name + ' ' + employee.last_name).toLowerCase()
    return fullName.includes(searchLower) || employee.id.toString().includes(search.value)
  })
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

    <h1 class="text-4xl font-bold text-center mb-12">Manage Employees</h1>

    <div class="flex justify-between mb-6 gap-4">
      <input v-model="search" type="text" placeholder="Search"
        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />

      <Link href="/addnewemployee"
        class="bg-green-500 hover:bg-green-600 font-semibold rounded-md px-4 py-2 transition whitespace-nowrap">
        Add Employee
      </Link>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
      <table class="min-w-full text-left whitespace-nowrap transition">
        <thead class="bg-gray-400">
          <tr>
            <th v-for="column in tableColumns" :key="column.key"
              :class="[column.align === 'center' ? 'text-center' : '']">
              {{ column.label }}
            </th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="employee in filteredEmployees" :key="employee.id" class="border-t-4 border-gray-200">
            <td>{{ employee.id }}</td>
            <td>{{ employee.first_name }} {{ employee.last_name }}</td>
            <td>{{ employee.department?.name ?? 'N/A' }}</td>
            <td>
              <div class="flex justify-center gap-3">
                <Link v-for="action in actionButtons" :key="action.label" :href="action.href(employee.id)"
                  :method="action.method" :as="action.as"
                  :class="[action.color, 'inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold transition']">
                  {{ action.label }}
                </Link>
              </div>
            </td>
          </tr>

          <tr v-if="filteredEmployees.length === 0">
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