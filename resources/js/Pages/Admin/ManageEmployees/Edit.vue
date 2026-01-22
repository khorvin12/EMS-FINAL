<script setup>
import { reactive } from 'vue'

const form = reactive({
  name: '',
  email: '',
  employee_id: '',
  dob: '',
  gender: '',
  civil_status: '',
  designation: '',
  department: '',
  salary: '',
  role: ''
})

const submit = () => {
  console.log(form)
}
</script>
<template>
    <!-- Main Content -->
    <main class="flex-1 p-8">
      <div class="bg-white border-4 border-yellow-400 rounded-lg p-8 max-w-5xl mx-auto">

        <h1 class="text-2xl font-bold mb-6">
          Edit Employee
        </h1>

        <form @submit.prevent="submit" class="grid grid-cols-2 gap-6">

          <!-- Name -->
          <div>
            <label class="text-sm font-semibold">Name</label>
            <input
              v-model="form.name"
              type="text"
              placeholder="Enter Name"
              class="w-full border rounded px-3 py-2 mt-1"
            />
          </div>

          <!-- Email -->
          <div>
            <label class="text-sm font-semibold">Email</label>
            <input
              v-model="form.email"
              type="email"
              placeholder="Enter Email"
              class="w-full border rounded px-3 py-2 mt-1"
            />
          </div>

          <!-- Employee ID -->
          <div>
            <label class="text-sm font-semibold">Employee ID</label>
            <input
              v-model="form.employee_id"
              type="text"
              placeholder="Enter ID"
              class="w-full border rounded px-3 py-2 mt-1"
            />
          </div>

          <!-- Date of Birth -->
          <div>
            <label class="text-sm font-semibold">Date of Birth</label>
            <input
              v-model="form.dob"
              type="date"
              class="w-full border rounded px-3 py-2 mt-1"
            />
          </div>

          <!-- Gender -->
          <div>
            <label class="text-sm font-semibold">Gender</label>
            <select
              v-model="form.gender"
              class="w-full border rounded px-3 py-2 mt-1"
            >
              <option value="">Select Gender</option>
              <option>Male</option>
              <option>Female</option>
            </select>
          </div>

          <!-- Civil Status -->
          <div>
            <label class="text-sm font-semibold">Civil Status</label>
            <select
              v-model="form.civil_status"
              class="w-full border rounded px-3 py-2 mt-1"
            >
              <option value="">Select Status</option>
              <option>Single</option>
              <option>Married</option>
            </select>
          </div>

          <!-- Designation -->
          <div>
            <label class="text-sm font-semibold">Designation</label>
            <input
              v-model="form.designation"
              type="text"
              placeholder="Designation"
              class="w-full border rounded px-3 py-2 mt-1"
            />
          </div>

          <!-- Department -->
          <div>
            <label class="text-sm font-semibold">Department</label>
            <select
              v-model="form.department"
              class="w-full border rounded px-3 py-2 mt-1"
            >
              <option value="">Select Department</option>
              <option>HR</option>
              <option>IT</option>
              <option>Finance</option>
            </select>
          </div>

          <!-- Salary -->
          <div>
            <label class="text-sm font-semibold">Salary</label>
            <input
              v-model="form.salary"
              type="number"
              placeholder="Salary"
              class="w-full border rounded px-3 py-2 mt-1"
            />
          </div>

          <!-- Role -->
          <div>
            <label class="text-sm font-semibold">Role</label>
            <select
              v-model="form.role"
              class="w-full border rounded px-3 py-2 mt-1"
            >
              <option value="">Select Role</option>
              <option>Admin</option>
              <option>Employee</option>
            </select>
          </div>

          <!-- Button -->
          <div class="col-span-2 mt-6">
            <button
              type="submit"
              class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded font-semibold"
            >
              Update Employee
            </button>
          </div>
        </form>
      </div>
    </main>
</template>
