<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

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
  { label: 'ID', key: 'id' },
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
    color: 'bg-yellow-500 hover:bg-yellow-600'
  },
  {
    label: 'Delete',
    href: (id) => `/delete/${id}`,
    color: 'bg-red-500 hover:bg-red-600',
    method: 'delete',
    as: 'button'
  }
]

const paginationButtons = computed(() => [
  {
    enabled: !!props.employees.prev_page_url,
    href: props.employees.prev_page_url,
    label: '‹'
  },
  {
    enabled: !!props.employees.next_page_url,
    href: props.employees.next_page_url,
    label: '›'
  }
])
</script>

<template>
  <div class="flex flex-col h-screen">
    <main class="flex-1 p-8 bg-gray-100 overflow-y-auto">

      <h1 class="text-3xl font-bold text-center mb-6">Manage Employees</h1>

      <div class="flex justify-between items-center mb-4">
        <input
          v-model="search"
          type="text"
          placeholder="Search by Name or ID"
          class="px-4 py-2 border rounded-md w-64 focus:ring focus:outline-none"
        />
        <Link
          href="/addnewemployee"
          class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-md font-semibold"
        >
          Add New Employee
        </Link>
      </div>

      <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-left">
          <thead class="bg-gray-200">
            <tr>
              <th
                v-for="column in tableColumns"
                :key="column.key"
                :class="['px-6 py-3', column.align === 'center' ? 'text-center' : '']"
              >
                {{ column.label }}
              </th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="employee in filteredEmployees"
              :key="employee.id"
              class="border-b hover:bg-gray-50"
            >
              <td class="px-6 py-3">{{ employee.id }}</td>
              <!-- FIX: use first_name + last_name instead of name -->
              <td class="px-6 py-3">{{ employee.first_name }} {{ employee.last_name }}</td>
              <td class="px-6 py-3">{{ employee.department?.name ?? 'N/A' }}</td>
              <td class="py-4 px-6">
                <div class="flex justify-center gap-2">
                  <Link
                    v-for="action in actionButtons"
                    :key="action.label"
                    :href="action.href(employee.id)"
                    :method="action.method"
                    :as="action.as"
                    :class="[action.color, 'text-white px-5 py-2 rounded-md inline-block']"
                  >
                    {{ action.label }}
                  </Link>
                </div>
              </td>
            </tr>

            <tr v-if="filteredEmployees.length === 0">
              <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                No employees found
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="flex justify-end items-center mt-4 space-x-2">
        <Link
          v-if="paginationButtons[0].enabled"
          :href="paginationButtons[0].href"
          class="w-8 h-8 flex items-center justify-center bg-gray-800 text-white rounded-full hover:bg-gray-700"
        >
          {{ paginationButtons[0].label }}
        </Link>
        <button
          v-else
          disabled
          class="w-8 h-8 flex items-center justify-center bg-gray-300 text-gray-500 rounded-full cursor-not-allowed"
        >
          {{ paginationButtons[0].label }}
        </button>

        <span class="font-semibold">{{ employees.current_page }}</span>
        <span class="text-gray-500">of {{ employees.last_page }}</span>

        <Link
          v-if="paginationButtons[1].enabled"
          :href="paginationButtons[1].href"
          class="w-8 h-8 flex items-center justify-center bg-gray-800 text-white rounded-full hover:bg-gray-700"
        >
          {{ paginationButtons[1].label }}
        </Link>
        <button
          v-else
          disabled
          class="w-8 h-8 flex items-center justify-center bg-gray-300 text-gray-500 rounded-full cursor-not-allowed"
        >
          {{ paginationButtons[1].label }}
        </button>
      </div>

    </main>
  </div>
</template>