<script setup>
import { reactive } from 'vue'

const form = reactive({
  last_name: '',
  first_name: '',
  employee_id: '',
  dob: '',
  gender: '',
  civil_status: '',
})

const submit = () => {
  console.log(form)
}
</script>

<template>
  <div class="max-w-4xl mx-auto bg-white border-4 border-green-500 p-6 rounded">

    <!-- Title -->
    <h2 class="text-xl font-bold mb-6">Add New Employee</h2>

    <!-- Form -->
    <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">

      <!-- Name -->
      <div>
        <label class="text-sm font-semibold">Name</label>
        <input
          type="text"
          v-model="form.last_name"
          placeholder="Last name"
          class="w-full mt-1 border rounded px-3 py-2"
        />
      </div>

      <div>
        <label class="text-sm font-semibold invisible">Name</label>
        <input
          type="text"
          v-model="form.first_name"
          placeholder="FirstName"
          class="w-full mt-1 border rounded px-3 py-2"
        />
      </div>

      <!-- Employee ID -->
      <div>
        <label class="text-sm font-semibold">Employee ID</label>
        <input
          type="text"
          v-model="form.employee_id"
          placeholder="Enter ID"
          class="w-full mt-1 border rounded px-3 py-2"
        />
      </div>

      <!-- Date of Birth -->
      <div>
        <label class="text-sm font-semibold">Date of Birth</label>
        <input
          type="date"
          v-model="form.dob"
          class="w-full mt-1 border rounded px-3 py-2"
        />
      </div>

      <!-- Gender -->
      <div>
        <label class="text-sm font-semibold">Gender</label>
        <select
          v-model="form.gender"
          class="w-full mt-1 border rounded px-3 py-2"
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
          class="w-full mt-1 border rounded px-3 py-2"
        >
          <option value="">Select Status</option>
          <option>Single</option>
          <option>Married</option>
        </select>
      </div>

      <!-- Button -->
      <div class="col-span-2 mt-6">
        <button
          type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold"
        >
          Confirm
        </button>
      </div>

    </form>
  </div>
</template>
