<script setup>
import { reactive, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

// Props from controller
const props = defineProps({
  departments: Array
})

const form = reactive({
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

const errorMessage = ref('')

function submit() {
  errorMessage.value = ''

  Inertia.post('/employees', { ...form }, {
    onError: errors => {
      errorMessage.value = Object.values(errors).flat().join(', ')
    },
    onSuccess: () => {
      Object.keys(form).forEach(key => form[key] = '')
      alert('Employee added successfully!')
    }
  })
}
</script>

<template>
  <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Add New Employee</h2>

    <div v-if="errorMessage" class="text-red-500 mb-4">{{ errorMessage }}</div>

    <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">
      <div>
        <label>First Name</label>
        <input type="text" v-model="form.first_name" required class="w-full border rounded px-2 py-1"/>
      </div>
      <div>
        <label>Last Name</label>
        <input type="text" v-model="form.last_name" required class="w-full border rounded px-2 py-1"/>
      </div>
      <div>
        <label>Employee ID</label>
        <input type="text" v-model="form.employee_id" required class="w-full border rounded px-2 py-1"/>
      </div>
      <div>
        <label>Email</label>
        <input type="email" v-model="form.email" required class="w-full border rounded px-2 py-1"/>
      </div>
      <div>
        <label>Phone</label>
        <input type="text" v-model="form.phone" required class="w-full border rounded px-2 py-1"/>
      </div>
      <div>
        <label>Department</label>
        <select v-model="form.department_id" required class="w-full border rounded px-2 py-1">
          <option value="">Select Department</option>
          <option v-for="dept in departments" :key="dept.id" :value="dept.id">
            {{ dept.name }}
          </option>
        </select>
      </div>
      <div>
        <label>Date of Birth</label>
        <input type="date" v-model="form.dob" required class="w-full border rounded px-2 py-1"/>
      </div>
      <div>
        <label>Gender</label>
        <select v-model="form.gender" required class="w-full border rounded px-2 py-1">
          <option value="">Select Gender</option>
          <option>Male</option>
          <option>Female</option>
        </select>
      </div>
      <div>
        <label>Civil Status</label>
        <select v-model="form.civil_status" required class="w-full border rounded px-2 py-1">
          <option value="">Select Status</option>
          <option>Single</option>
          <option>Married</option>
        </select>
      </div>
      <div>
        <label>Role</label>
        <select v-model="form.role" required class="w-full border rounded px-2 py-1">
          <option value="employee">Employee</option>
          <option value="hr">HR</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div>
        <label>Hire Date</label>
        <input type="date" v-model="form.hire_date" required class="w-full border rounded px-2 py-1"/>
      </div>
      <div>
        <label>Salary</label>
        <input type="number" v-model="form.salary" required class="w-full border rounded px-2 py-1"/>
      </div>

      <div class="col-span-2">
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded mt-4 hover:bg-blue-700">
          Add Employee
        </button>
      </div>
    </form>
  </div>
</template>
