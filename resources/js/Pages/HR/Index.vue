<template>
  <div class="p-6">
    <h1 class="text-3xl font-bold mb-8">Welcome back HR</h1>
    
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
import { usePage, router } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth?.user ?? null)

function logout() {
  router.post('/logout')
}
</script>