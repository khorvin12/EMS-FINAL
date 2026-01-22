<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'

const page = usePage()
const showSuccess = ref(!!page.props.flash.success)

// Define props to receive data from Laravel controller
const props = defineProps({
  employees: {
    type: Object,
    required: true
  }
})

const search = ref('')

// Auto-hide success message
watch(
  () => page.props.flash.success,
  (value) => {
    if (value) {
      showSuccess.value = true
      setTimeout(() => {
        showSuccess.value = false
      }, 3000)
    }
  }
)

// Filter employees based on search
const filteredEmployees = computed(() => {
  if (!search.value) {
    return props.employees.data
  }
  return props.employees.data.filter(employee => 
    employee.name.toLowerCase().includes(search.value.toLowerCase()) ||
    employee.id.toString().includes(search.value)
  )
})
</script>

<template>
  <div class="flex flex-col h-screen">
    <main class="flex-1 p-8 bg-gray-100 overflow-y-auto">
    
    <!-- Success Message -->
    <transition
      enter-active-class="transition ease-out duration-300"
      enter-from-class="opacity-0 translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-300"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-2"
    >
      <div
        v-if="showSuccess"
        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"
      >
        {{ $page.props.flash.success }}
      </div>
    </transition>

      <!-- Title -->
      <h1 class="text-3xl font-bold text-center mb-6">
        Manage Employees
      </h1>

      <!-- Search + Button -->
      <div class="flex justify-between items-center mb-4">
        <input
          v-model="search"
          type="text"
          placeholder="Search by Name or ID"
          class="px-4 py-2 border rounded-md w-64 focus:ring focus:outline-none"
        />

        <Link href="/addnewemployee" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-md">
          Add New Employee
        </Link>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-left">
          <thead class="bg-gray-200">
            <tr>
              <th class="px-6 py-3">ID</th>
              <th class="px-6 py-3">Name</th>
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
              <td class="px-6 py-3">{{ employee.name }}</td>
              <td class="px-6 py-3">{{ employee.department?.name ?? 'N/A' }}</td>

              <td class="py-4 px-6">
                <div class="flex justify-center gap-2">
                  <Link :href="`/view/${employee.id}`" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-md">
                    View
                  </Link>

                  <Link :href="`/edit/${employee.id}`" class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-md">
                    Edit
                  </Link>

                  <Link :href="`/delete/${employee.id}`" method="delete" as="button" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-md">
                    Delete
                  </Link>
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