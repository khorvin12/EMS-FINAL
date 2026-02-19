<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  employee: {
    type: Object,
    required: true
  }
})

// Format date helper function
const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const employeeFields = [
  { label: 'Employee ID', value: (emp) => emp.employee_id },
  { label: 'Name', value: (emp) => emp.name },
  { label: 'Email', value: (emp) => emp.email },
  { label: 'Phone', value: (emp) => emp.phone },
  { label: 'Department', value: (emp) => emp.department?.name ?? 'N/A' },
  { label: 'Role', value: (emp) => emp.role, class: 'capitalize' },
  { label: 'Date of Birth', value: (emp) => formatDate(emp.dob) },
  { label: 'Gender', value: (emp) => emp.gender },
  { label: 'Civil Status', value: (emp) => emp.civil_status },
  { label: 'Hire Date', value: (emp) => formatDate(emp.hire_date) },
  { label: 'Salary', value: (emp) => `₱${Number(emp.salary).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` }
]
</script>

<template>
  <div class="bg-white border-4 border-blue-500 rounded-lg p-8 max-w-7xl mx-auto overflow-hidden overflow-x-auto">

    <h1 class="text-2xl font-bold mb-6">View Employee</h1>

    <div class="flex flex-col md:flex-row gap-10 items-start">

      <div class="flex-shrink-0">
        <div class="w-100 h-100 overflow-hidden rounded-full bg-slate-100 border-4 border-blue-500 shadow-sm">
          <img src="https://picsum.photos/200"
            class="w-full h-full object-cover">
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 text-gray-800 flex-grow ml-16">

        <div v-for="field in employeeFields" :key="field.label">
          <p class="text-sm font-bold text-gray-600 uppercase tracking-wide">{{ field.label }}</p>
          <p class="mt-1 text-lg text-gray-900" :class="field.class">
            {{ field.value(employee) }}
          </p>
        </div>

      </div>
    </div>

    <div class="mt-12">
      <Link href="/manageemployees"
        class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md transition-colors">
        ← Back to Employees
      </Link>
    </div>
  </div>
</template>