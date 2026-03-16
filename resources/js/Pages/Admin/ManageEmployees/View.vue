<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  employee: { type: Object, required: true }
})

const displayValue = (val) => val || '—'

const formatDate = (dateString) => {
  if (!dateString) return '—'
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric', month: 'long', day: 'numeric'
  })
}

const employeeFields = [
  { label: 'Employee ID',  value: (e) => e.employee_id },
  { label: 'Name',         value: (e) => `${e.first_name} ${e.last_name}` },
  { label: 'Email',        value: (e) => e.email },
  { label: 'Phone',        value: (e) => displayValue(e.phone) },
  { label: 'Department',   value: (e) => e.department?.name ?? 'Unassigned' },
  { label: 'Role',         value: (e) => e.role, class: 'capitalize' },
  { label: 'Date of Birth',value: (e) => formatDate(e.dob) },
  { label: 'Gender',       value: (e) => displayValue(e.gender) },
  { label: 'Civil Status', value: (e) => displayValue(e.civil_status) },
  { label: 'Hire Date',    value: (e) => formatDate(e.hire_date) },
  {
    label: 'Salary',
    value: (e) => e.salary
      ? `₱${Number(e.salary).toLocaleString('en-US', { minimumFractionDigits: 2 })}`
      : '—'
  }
]
</script>

<template>

  <Head title=" | Employee Details" />

  <div class="flex justify-center items-center py-24">
    <div class="bg-white border-4 border-blue-400 rounded-lg p-6 max-w-3xl w-full shadow-lg">

      <div class="flex justify-end items-center gap-4">
        <Link href="/manageemployees" class="text-blue-500 hover:text-blue-600 transition-colors">
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