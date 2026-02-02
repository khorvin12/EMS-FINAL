<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

function handleLogin() {
  form.post('/login')
}
</script>

<script>
export default {
  layout: null,
}
</script>

<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-r from-green-200 to-blue-200 font-sans">
    <h1 class="text-4xl font-bold text-blue-700 mb-8 drop-shadow">
      EMPLOYEE MANAGEMENT
    </h1>

    <div class="bg-gradient-to-r from-green-400 to-lime-300 p-6 rounded-lg w-80 shadow-md">
      <h2 class="text-xl font-semibold mb-4 text-gray-800">Login</h2>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <input
          type="email"
          v-model="form.email"
          placeholder="Enter Email"
          class="bg-white w-full px-3 py-2 border border-gray-300 rounded"
          required
        />

        <input
          type="password"
          v-model="form.password"
          placeholder="Enter Password"
          class="bg-white w-full px-3 py-2 border border-gray-300 rounded"
          required
        />

        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded font-semibold"
        >
          Login
        </button>
      </form>
    </div>
  </div>
</template>
