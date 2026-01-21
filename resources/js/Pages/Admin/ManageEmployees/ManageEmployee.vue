<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, watch, computed } from 'vue'

const page = usePage()
const showSuccess = ref(!!page.props.flash.success)

const search = ref('')
const employees = ref([])

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

const filteredEmployees = computed(() => {
  if (!search.value) return employees.value
  return employees.value.filter(e =>
    e.id.toString().includes(search.value)
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
          placeholder="Search by Employee ID"
          class="px-4 py-2 border rounded-md w-64 focus:ring focus:outline-none"
        />

        <Link href="/employees/create">
  <button class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-md">
    Add New Employee
  </button>
</Link>

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
              class="border-b"
            >
              <td class="px-6 py-3">{{ employee.id }}</td>
              <td class="px-6 py-3">{{ employee.birth }}</td>
              <td class="px-6 py-3">{{ employee.department }}</td>

              <td class="px-6 py-3 text-center space-x-2">
                <button class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-1 rounded">
                  View
                </button>
                <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                  Edit
                </button>
                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-end items-center mt-4 space-x-2">
        <button class="w-8 h-8 flex items-center justify-center bg-gray-800 text-white rounded-full">
          ‹
        </button>
        <span class="font-semibold">1</span>
        <span class="text-gray-500">2</span>
        <span class="text-gray-500">3</span>
        <button class="w-8 h-8 flex items-center justify-center bg-gray-800 text-white rounded-full">
          ›
        </button>
      </div>

    </main>
  </div>
</template>
