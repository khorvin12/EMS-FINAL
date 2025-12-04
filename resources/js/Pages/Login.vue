<script setup>
import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const email = ref('')
const password = ref('')
const rememberMe = ref(false)

function handleLogin() {
  Inertia.post('/login', {
    email: email.value,
    password: password.value,
    remember: rememberMe.value,
  })
}
</script>

<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-r from-green-200 to-blue-200 font-sans">
    <h1 class="font-style-gagalin text-4xl font-bold text-blue-700 mb-8 drop-shadow">EMPLOYEE MANAGEMENT</h1>
    <div class="bg-gradient-to-r from-green-400 to-lime-300 p-6 rounded-lg w-80 shadow-md">
      <h2 class="text-xl font-semibold mb-4 text-gray-800">Login</h2>
      <form @submit.prevent="handleLogin" class="space-y-4">
        <input
          type="email"
          v-model="email"
          placeholder="Enter Email"
          class="bg-white w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
          required
        />
        <input
          type="password"
          v-model="password"
          placeholder="Enter Password"
          class="bg-white w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
          required
        />
        <div class="flex items-center justify-between text-sm text-gray-700">
          <label class="flex items-center space-x-2">
            <input type="checkbox" v-model="rememberMe" class="accent-blue-600" />
            <span>Remember me</span>
          </label>
        </div>
        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition-colors font-semibold"
        >
          Login
        </button>
      </form>
    </div>
  </div>
</template>
