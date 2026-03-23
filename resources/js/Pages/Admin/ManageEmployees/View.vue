<script setup>
import { Head, Link } from '@inertiajs/vue3'

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
  { label: 'Employee ID', value: (emp) => 'EMP-' + String(emp.id).padStart(3, '0') },
  { label: 'Name', value: (emp) => `${emp.first_name} ${emp.last_name}` },
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

  <Head title=" | Employee Details" />

  <div class="flex justify-center items-center py-24">
    <div class="bg-white border-4 border-blue-400 rounded-lg p-6 max-w-3xl w-full shadow-lg">

      <div class="flex justify-end items-center gap-4">
        <Link href="/admin/manageemployees" class="text-blue-500 hover:text-blue-600  transition-colors">
          ← Back
        </Link>
      </div>

      <h1 class="text-3xl font-bold mb-6">Employee Details</h1>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-800">
        <div v-for="field in employeeFields" :key="field.label">
          <p class="text-sm font-bold text-gray-600">{{ field.label }}</p>
          <p class="text-gray-900" :class="field.class">
            {{ field.value(employee) }}
          </p>
        </div>
      </div>

    </div>
  </div>
</template>