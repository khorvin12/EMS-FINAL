<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  employee: {
    type: Object,
    required: true
  }
})

// Helper for consistency
const displayValue = (val) => val || '—'

const formatDate = (dateString) => {
  if (!dateString) return '—'
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric', month: 'long', day: 'numeric'
  })
}

const employeeFields = [
  { label: 'Employee ID', value: (e) => e.employee_id },
  { label: 'Name', value: (e) => e.name },
  { label: 'Email', value: (e) => e.email },
  { label: 'Phone', value: (e) => displayValue(e.phone) },
  { label: 'Department', value: (e) => e.department?.name ?? 'Unassigned' },
  { label: 'Role', value: (e) => e.role, class: 'capitalize' },
  { label: 'Date of Birth', value: (e) => formatDate(e.dob) },
  { label: 'Gender', value: (emp) => emp.gender },
  { label: 'Civil Status', value: (emp) => emp.civil_status },
  { label: 'Hire Date', value: (e) => formatDate(e.hire_date) },
  {
    label: 'Salary',
    value: (e) => e.salary ? `₱${Number(e.salary).toLocaleString('en-US', { minimumFractionDigits: 2 })}` : '—'
  }
]
</script>

<template>
  <div class="max-w-5xl mx-auto py-8 px-4">
    <div class="mb-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-900">Employee Profile</h1>
      <Link href="/manageemployees" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
        ← Back to Directory
      </Link>
    </div>

    <div class="bg-white shadow border-4 border-blue-500 rounded-lg overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg font-semibold text-gray-800 uppercase tracking-wider">
          Personal & Professional Information
        </h3>
      </div>

      <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
          <div v-for="field in employeeFields" :key="field.label" class="border-b border-gray-50 pb-2">
            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
              {{ field.label }}
            </dt>
            <dd class="mt-1 text-md text-gray-900 font-medium" :class="field.class">
              {{ field.value(employee) }}
            </dd>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>