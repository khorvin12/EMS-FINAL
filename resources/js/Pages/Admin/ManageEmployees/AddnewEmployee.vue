<script setup>
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'

// Reactive form object
const form = reactive({
  last_name: '',
  first_name: '',
  employee_id: '',
  dob: '',
  gender: '',
  civil_status: '',
  role: 'employee',
  email: '',    
})

// Submit function
function submit() {
  // Set URL based on role
  let url = '/employees'; // default Employee
  if (form.role === 'hr') url = '/hr'; // HR
  if (form.role === 'admin') url = '/admins'; // only if you create this route

  Inertia.post(url, { ...form })


  // Reset form after submission
  form.last_name = ''
  form.first_name = ''
  form.employee_id = ''
  form.dob = ''
  form.gender = ''
  form.civil_status = ''
  form.role = 'employee'
  form.email = ''

  alert('User added!')
}
</script>

<template>
  <div class="max-w-4xl mx-auto bg-white border-4 border-green-500 p-6 rounded">

    <!-- Title -->
    <h2 class="text-xl font-bold mb-6">Add New Employee</h2>

    <!-- Form -->
    <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">

      <!-- Last Name -->
      <div>
        <label class="text-sm font-semibold">Last Name</label>
        <input type="text" v-model="form.last_name" placeholder="Last Name"
          class="w-full mt-1 border rounded px-3 py-2" />
      </div>

      <!-- First Name -->
      <div>
        <label class="text-sm font-semibold invisible">First Name</label>
        <input type="text" v-model="form.first_name" placeholder="First Name"
          class="w-full mt-1 border rounded px-3 py-2" />
      </div>

      <!-- Employee ID -->
      <div>
        <label class="text-sm font-semibold">Employee ID</label>
        <input type="text" v-model="form.employee_id" placeholder="Enter ID"
          class="w-full mt-1 border rounded px-3 py-2" />
      </div>


            <div>
        <label class="text-sm font-semibold">Email</label>
        <input type="text" v-model="form.email" placeholder="Enter Email"
          class="w-full mt-1 border rounded px-3 py-2" />
      </div>

      
      <!-- Date of Birth -->
      <div>
        <label class="text-sm font-semibold">Date of Birth</label>
        <input type="date" v-model="form.dob" class="w-full mt-1 border rounded px-3 py-2" />
      </div>

      <!-- Gender -->
      <div>
        <label class="text-sm font-semibold">Gender</label>
        <select v-model="form.gender" class="w-full mt-1 border rounded px-3 py-2">
          <option value="">Select Gender</option>
          <option>Male</option>
          <option>Female</option>
          <option>gay</option>
          <option>dino</option>
          <option>car</option>
          <option>spoon</option>
        </select>
      </div>

      <!-- Civil Status -->
      <div>
        <label class="text-sm font-semibold">Civil Status</label>
        <select v-model="form.civil_status" class="w-full mt-1 border rounded px-3 py-2">
          <option value="">Select Status</option>
          <option>Single</option>
          <option>Married</option>
          <option>broken</option>
          <option>kamot lng</option>
          <option>ala label</option>
          <option>saging</option>
        </select>
      </div>

      <!-- Role -->
      <div>
        <label class="text-sm font-semibold">Role</label>
        <select v-model="form.role" class="w-full mt-1 border rounded px-3 py-2">
          <option value="employee">Employee</option>
          <option value="hr">HR</option>
          <option value="admin">Admin</option>
        </select>
      </div>



      <!-- Submit Button -->
      <div class="col-span-2 mt-6">
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold">
          Confirm
        </button>
      </div>

    </form>
  </div>
</template>
