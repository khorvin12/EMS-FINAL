<script setup>
import { reactive, ref } from 'vue'
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
  phone: '',          // Added phone
  department_id: '',  // Added department_id
  role_id: '',        // Added role_id
  hire_date: '',      // Added hire_date
  salary: '',         // Added salary
})

const errorMessage = ref('');  // For capturing validation errors

// Submit function
function submit() {
  errorMessage.value = '';  // Reset error message
  // Validate email format
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!form.email.match(emailPattern)) {
    errorMessage.value = 'Please enter a valid email address';
    return;
  }

  // Set URL based on role
  let url = '/employees'; // default Employee
  if (form.role === 'hr') url = '/hr'; // HR
  if (form.role === 'admin') url = '/admins'; // only if you create this route

  Inertia.post(url, { ...form })

  // Reset form after submission
  Object.keys(form).forEach(key => form[key] = ''); // Reset all form fields

  alert('User added!')
}
</script>

<template>
  <div class="max-w-4xl mx-auto bg-white border-4 border-green-500 p-6 rounded">

    <!-- Title -->
    <h2 class="text-xl font-bold mb-6">Add New Employee</h2>

    <!-- Error Message -->
    <div v-if="errorMessage" class="text-red-500 mb-4">{{ errorMessage }}</div>

    <!-- Form -->
    <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">

      <!-- Last Name -->
      <div>
        <label class="text-sm font-semibold">Last Name</label>
        <input type="text" v-model="form.last_name" placeholder="Last Name"
          class="w-full mt-1 border rounded px-3 py-2" required />
      </div>

      <!-- First Name -->
      <div>
        <label class="text-sm font-semibold">First Name</label>
        <input type="text" v-model="form.first_name" placeholder="First Name"
          class="w-full mt-1 border rounded px-3 py-2" required />
      </div>

      <!-- Employee ID -->
      <div>
        <label class="text-sm font-semibold">Employee ID</label>
        <input type="text" v-model="form.employee_id" placeholder="Enter ID"
          class="w-full mt-1 border rounded px-3 py-2" required />
      </div>

      <!-- Email -->
      <div>
        <label class="text-sm font-semibold">Email</label>
        <input type="email" v-model="form.email" placeholder="Enter Email"
          class="w-full mt-1 border rounded px-3 py-2" required />
      </div>

      <!-- Phone -->
      <div>
        <label class="text-sm font-semibold">Phone</label>
        <input type="text" v-model="form.phone" placeholder="Enter Phone"
          class="w-full mt-1 border rounded px-3 py-2" required />
      </div>

      <!-- Department ID -->
      <div>
        <label class="text-sm font-semibold">Department ID</label>
        <input type="number" v-model="form.department_id" placeholder="Department ID"
          class="w-full mt-1 border rounded px-3 py-2" required />
      </div>

      <!-- Role ID -->
      <div>
        <label class="text-sm font-semibold">Role ID</label>
        <input type="number" v-model="form.role_id" placeholder="Role ID"
          class="w-full mt-1 border rounded px-3 py-2" required />
      </div>

      <!-- Date of Birth -->
      <div>
        <label class="text-sm font-semibold">Date of Birth</label>
        <input type="date" v-model="form.dob" class="w-full mt-1 border rounded px-3 py-2" required />
      </div>

      <!-- Gender -->
      <div>
        <label class="text-sm font-semibold">Gender</label>
        <select v-model="form.gender" class="w-full mt-1 border rounded px-3 py-2" required>
          <option value="">Select Gender</option>
          <option>Male</option>
          <option>Female</option>
        </select>
      </div>

      <!-- Civil Status -->
      <div>
        <label class="text-sm font-semibold">Civil Status</label>
        <select v-model="form.civil_status" class="w-full mt-1 border rounded px-3 py-2" required>
          <option value="">Select Status</option>
          <option>Single</option>
          <option>Married</option>
        </select>
      </div>

      <!-- Role -->
      <div>
        <label class="text-sm font-semibold">Role</label>
        <select v-model="form.role" class="w-full mt-1 border rounded px-3 py-2" required>
          <option value="employee">Employee</option>
          <option value="hr">HR</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <!-- Hire Date -->
      <div>
        <label class="text-sm font-semibold">Hire Date</label>
        <input type="date" v-model="form.hire_date" class="w-full mt-1 border rounded px-3 py-2" required />
      </div>

      <!-- Salary -->
      <div>
        <label class="text-sm font-semibold">Salary</label>
        <input type="number" v-model="form.salary" placeholder="Enter Salary"
          class="w-full mt-1 border rounded px-3 py-2" required />
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