<script setup>
import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const name = ref('')
const email = ref('')
const password = ref('')
const role = ref('employee')

// Add Employee / HR / Admin
function addUser() {
  Inertia.post('/admin/employees', {
    name: name.value,
    email: email.value,
    password: password.value,
    role: role.value
  })

  name.value = ''
  email.value = ''
  password.value = ''
  role.value = 'employee'

  alert('User added!')
}



function logout() {
  Inertia.post('/logout')
}
</script>

<template>
  <div>
    <h2>Add User</h2>

    <form @submit.prevent="addUser">
      <input v-model="name" placeholder="Name" /><br />
      <input v-model="email" placeholder="Email" /><br />
      <input v-model="password" type="password" placeholder="Password" /><br />

      <label>Role:</label>
      <select v-model="role">
        <option value="employee">Employee</option>
        <option value="hr">HR</option>
        <option value="admin">Admin</option>
      </select><br />

      <button type="submit">Add</button>
    </form>

    <button
      @click="logout"
      class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
    >
      Logout
    </button>
  </div>
</template>
