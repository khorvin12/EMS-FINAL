<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  departments: Array
})

const form = useForm({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  department_id: '',
  dob: '',
  gender: '',
  civil_status: '',
  role: 'employee',
  hire_date: '',
  salary: ''
})

function submit() {
  form.post('/admin/employees', {
    preserveScroll: true,
    onSuccess: () => { }
  })
}

const formFields = [
  { name: 'first_name', label: 'First Name', type: 'text', placeholder: 'First Name' },
  { name: 'last_name', label: 'Last Name', type: 'text', placeholder: 'Last Name' },
  { name: 'email', label: 'Email', type: 'email', placeholder: 'Enter Email' },
  { name: 'phone', label: 'Phone', type: 'text', placeholder: 'Enter Phone' },
  { name: 'department_id', label: 'Department', type: 'select', options: computed(() => props.departments), placeholder: 'Select Department' },
  { name: 'dob', label: 'Date of Birth', type: 'date' },
  { name: 'gender', label: 'Gender', type: 'select', options: ['Male', 'Female'], placeholder: 'Select Gender' },
  { name: 'civil_status', label: 'Civil Status', type: 'select', options: ['Single', 'Married'], placeholder: 'Select Status' },
  { name: 'role', label: 'Role', type: 'select', options: [{ value: 'employee', label: 'Employee' }, { value: 'hr', label: 'HR' }, { value: 'admin', label: 'Admin' }] },
  { name: 'hire_date', label: 'Hire Date', type: 'date' },
  { name: 'salary', label: 'Salary', type: 'number', placeholder: 'Enter Salary' }
]
</script>

<template>
  <div class="bg-white border-4 border-green-500 rounded-lg p-8 max-w-4xl mx-auto">

    <Head title=" | Add Employee" />

    <h2 class="text-xl font-bold mb-6">Add Employee</h2>

    <!-- Success Message -->
    <div v-if="$page.props.flash?.success" class="text-green-500 mb-4 p-3 bg-green-50 rounded">
      {{ $page.props.flash.success }}
    </div>

    <!-- Error Message -->
    <div v-if="form.errors && Object.keys(form.errors).length" class="text-red-500 mb-4 p-3 bg-red-50 rounded">
      <ul class="list-disc list-inside">
        <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
      </ul>
    </div>

    <form @submit.prevent="submit" class="grid grid-cols-2 gap-6">

      <!-- Dynamic Form Fields -->
      <div v-for="field in formFields" :key="field.name">
        <label class="text-sm font-semibold">{{ field.label }}</label>

        <!-- Text/Email/Number/Date Inputs -->
        <input v-if="['text', 'email', 'number', 'date'].includes(field.type)" :type="field.type"
          v-model="form[field.name]" :placeholder="field.placeholder"
          class="w-full mt-1 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
          :class="{ 'border-red-500': form.errors[field.name] }" required />

        <!-- Select Inputs -->
        <select v-else-if="field.type === 'select'" v-model="form[field.name]"
          class="w-full mt-1 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
          :class="{ 'border-red-500': form.errors[field.name] }" required>
          <option v-if="field.placeholder" value="">{{ field.placeholder }}</option>

          <!-- For department options (array of objects) -->
          <template v-if="field.name === 'department_id'">
            <option v-for="dept in field.options.value" :key="dept.id" :value="dept.id">
              {{ dept.name }}
            </option>
          </template>

          <!-- For role options (array of objects with value/label) -->
          <template v-else-if="field.name === 'role'">
            <option v-for="opt in field.options" :key="opt.value" :value="opt.value">
              {{ opt.label }}
            </option>
          </template>

          <!-- For simple string arrays (gender, civil_status) -->
          <template v-else>
            <option v-for="opt in field.options" :key="opt" :value="opt">
              {{ opt }}
            </option>
          </template>
        </select>
      </div>

      <!-- Submit Button -->
      <div class="col-span-2 mt-6">
        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold disabled:opacity-50"
          :disabled="form.processing">
          {{ form.processing ? 'Submitting...' : 'Confirm' }}
        </button>
      </div>

    </form>
  </div>
</template>