<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

// Define props to receive data from Laravel controller
const props = defineProps({
  employees: {
    type: Object,
    required: true
  }
})

const search = ref('')

// Filter employees based on search
const filteredEmployees = computed(() => {
  if (!search.value) {
    return props.employees.data
  }
  return props.employees.data.filter(employee => 
    employee.id.toString().includes(search.value)
  )
})

// Format date helper function
const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  })
}
</script>

<template>
  <div class="flex flex-col h-screen">
    <main class="flex-1 p-8 bg-gray-100 overflow-y-auto">

      <!-- Title -->
      <h1 class="text-3xl font-bold text-center mb-6">
        Manage Employees
      </h1>

      <!-- Search + Button -->
      <div class="flex justify-between items-center mb-4">
        <input
          v-model="search"
          type="text"
          placeholder="Search by Employee ID"
          class="px-4 py-2 border rounded-md w-64 focus:ring focus:outline-none"
        />
        <div class="text-bold bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-md">
          <Link href="/addnewemployee">
            <i aria-hidden="true" /> Add New Employee
          </Link>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-left">
          <thead class="bg-gray-200">
            <tr>
              <th class="px-6 py-3">ID</th>
              <th class="px-6 py-3">Birth</th>
              <th class="px-6 py-3">Department</th>
              <th class="px-6 py-3 text-center">Action</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="employee in filteredEmployees"
              :key="employee.id"
              class="border-b hover:bg-gray-50"
            >
              <td class="px-6 py-3">{{ employee.id }}</td>
              <td class="px-6 py-3">{{ formatDate(employee.birth_date) }}</td>
              <td class="px-6 py-3">{{ employee.department }}</td>

              <td class="py-4 px-6">
                <div class="flex justify-center gap-2">
                  <div class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-md inline-block">
                    <Link :href="`/view/${employee.id}`">
                      View
                    </Link>
                  </div>

                  <div class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-md inline-block">
                    <Link :href="`/edit/${employee.id}`">
                      Edit
                    </Link>
                  </div>

                  <div class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-md inline-block">
                    <Link :href="`/delete/${employee.id}`" method="delete" as="button">
                      Delete
                    </Link>
                  </div>
                </div>
              </td>
            </tr>

            <!-- Empty state -->
            <tr v-if="filteredEmployees.length === 0">
              <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                No employees found
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-end items-center mt-4 space-x-2">
        <Link
          v-if="employees.prev_page_url"
          :href="employees.prev_page_url"
          class="w-8 h-8 flex items-center justify-center bg-gray-800 text-white rounded-full hover:bg-gray-700"
        >
          ‹
        </Link>
        <button
          v-else
          disabled
          class="w-8 h-8 flex items-center justify-center bg-gray-300 text-gray-500 rounded-full cursor-not-allowed"
        >
          ‹
        </button>

        <span class="font-semibold">{{ employees.current_page }}</span>
        <span class="text-gray-500">of {{ employees.last_page }}</span>

        <Link
          v-if="employees.next_page_url"
          :href="employees.next_page_url"
          class="w-8 h-8 flex items-center justify-center bg-gray-800 text-white rounded-full hover:bg-gray-700"
        >
          ›
        </Link>
        <button
          v-else
          disabled
          class="w-8 h-8 flex items-center justify-center bg-gray-300 text-gray-500 rounded-full cursor-not-allowed"
        >
          ›
        </button>
      </div>

    </main>
  </div>
</template>