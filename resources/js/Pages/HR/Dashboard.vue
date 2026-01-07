<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Welcome HR!</h1>
    
    <p v-if="user">You are logged in as: {{ user.name }}</p>
    <p v-else>Loading...</p>

    <!-- Logout Button -->
    <button
      @click="logout"
      class="mt-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
    >
      Logout
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'

// Get auth.user safely
const { auth } = usePage().props
const user = computed(() => auth.user)

// Logout function
function logout() {
  Inertia.post('/logout', {}, {
    onFinish: () => {
      console.log('Logged out successfully')
    },
  })
}
</script>
