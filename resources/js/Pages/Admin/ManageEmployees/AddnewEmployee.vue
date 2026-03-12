<script setup>
import { useForm } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'

const props = defineProps({
  departments: Array
})

const form = useForm({
  first_name: '',
  last_name: '',
  employee_id: '',
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

const isHRorAdmin = computed(() => form.role === 'hr' || form.role === 'admin')

function isRequired(fieldName) {
  const optionalForHR = ['department_id', 'phone', 'dob', 'gender', 'civil_status', 'hire_date', 'salary', 'employee_id']
  if (isHRorAdmin.value && optionalForHR.includes(fieldName)) return false
  return true
}

function submit() {
  form.post('/employees', {
    preserveScroll: true,
    onSuccess: () => {}
  })
}

const formFields = [
  { name: 'last_name', label: 'Last Name', type: 'text', placeholder: 'Last Name' },
  { name: 'first_name', label: 'First Name', type: 'text', placeholder: 'First Name' },
  { name: 'employee_id', label: 'Employee ID', type: 'text', placeholder: 'Enter ID' },
  { name: 'email', label: 'Email', type: 'email', placeholder: 'Enter Email' },
  { name: 'phone', label: 'Phone', type: 'text', placeholder: 'Enter Phone' },
  {
    name: 'department_id',
    label: 'Department',
    type: 'select',
    options: computed(() => props.departments),
    placeholder: 'Select Department'
  },
  { name: 'dob', label: 'Date of Birth', type: 'date' },
  {
    name: 'gender',
    label: 'Gender',
    type: 'select',
    options: ['Male', 'Female'],
    placeholder: 'Select Gender'
  },
  {
    name: 'civil_status',
    label: 'Civil Status',
    type: 'select',
    options: ['Single', 'Married'],
    placeholder: 'Select Status'
  },
  {
    name: 'role',
    label: 'Role',
    type: 'select',
    options: [
      { value: 'employee', label: 'Employee' },
      { value: 'hr', label: 'HR' },
      { value: 'admin', label: 'Admin' }
    ]
  },
  { name: 'hire_date', label: 'Hire Date', type: 'date' },
  { name: 'salary', label: 'Salary', type: 'number', placeholder: 'Enter Salary' }
]
</script>

<template>
  <div class="flex justify-center py-10">
    <div class="min-w-4xl mx-auto bg-white border-4 border-green-500 p-6 rounded-lg">

      <h2 class="text-xl font-bold mb-6">Add New Employee</h2>

      <div v-if="$page.props.flash?.success" class="text-green-500 mb-4 p-3 bg-green-50 rounded">
        {{ $page.props.flash.success }}
      </div>

      <div v-if="form.errors && Object.keys(form.errors).length" class="text-red-500 mb-4 p-3 bg-red-50 rounded">
        <ul class="list-disc list-inside">
          <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
        </ul>
      </div>

      <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">

        <div v-for="field in formFields" :key="field.name">
          <label class="text-sm font-semibold">
            {{ field.label }}
            <span v-if="!isRequired(field.name)" class="text-gray-400 font-normal text-xs">(optional)</span>
          </label>

          <!-- Text/Email/Number/Date Inputs -->
          <input
            v-if="['text', 'email', 'number', 'date'].includes(field.type)"
            :type="field.type"
            v-model="form[field.name]"
            :placeholder="field.placeholder"
            class="w-full mt-1 border rounded px-3 py-2 focus:outline-none focus:border-blue-400"
            :class="{ 'border-red-500': form.errors[field.name] }"
            :required="isRequired(field.name)"
          />

          <!-- Select Inputs -->
          <select
            v-else-if="field.type === 'select'"
            v-model="form[field.name]"
            class="w-full mt-1 border rounded px-3 py-2 focus:outline-none focus:border-blue-400"
            :class="{ 'border-red-500': form.errors[field.name] }"
            :required="isRequired(field.name)"
          >
            <option v-if="field.placeholder" value="">{{ field.placeholder }}</option>

            <template v-if="field.name === 'department_id'">
              <option v-for="dept in field.options.value" :key="dept.id" :value="dept.id">
                {{ dept.name }}
              </option>
            </template>

            <template v-else-if="field.name === 'role'">
              <option v-for="opt in field.options" :key="opt.value" :value="opt.value">
                {{ opt.label }}
              </option>
            </template>

            <template v-else>
              <option v-for="opt in field.options" :key="opt" :value="opt">
                {{ opt }}
              </option>
            </template>
          </select>
        </div>

        <div class="col-span-2 mt-6">
          <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold disabled:opacity-50"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Submitting...' : 'Confirm' }}
          </button>
        </div>

      </form>
    </div>
  </div>
</template>