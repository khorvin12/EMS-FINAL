<script setup>
import { useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: ''
})

const showSuccessModal = ref(false)

function submit() {
  form.post('/employee/change-password', {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      showSuccessModal.value = true
      router.post('/logout')
    },
    onError: (errors) => {
      console.error('Password change failed:', errors)
    }
  })
}
</script>

<template>
  <div class="flex items-center justify-center h-full">
    <div class="bg-white w-full max-w-sm px-6 py-4 rounded-md shadow-md border-4 border-green-600">
      <h1 class="text-xl font-bold mb-6 text-center">Change Password</h1>

      <!-- Success Message -->
      <div v-if="$page.props.flash?.success" class="text-green-500 mb-4 p-3 bg-green-50 rounded">
        {{ $page.props.flash.success }}
      </div>

      <!-- Error Messages -->
      <div v-if="form.errors && Object.keys(form.errors).length" class="text-red-500 mb-4 p-3 bg-red-50 rounded">
        <ul class="list-disc list-inside text-sm">
          <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
        </ul>
      </div>

      <form @submit.prevent="submit" class="grid grid-cols-1 gap-6 p-6">
        <div class="grid grid-cols-1">
          <label for="current_password" class="mb-1 font-semibold">Current Password</label>
          <input 
            type="password" 
            id="current_password"
            v-model="form.current_password"
            class="p-2 rounded-lg border border-slate-300"
            :class="{ 'border-red-500': form.errors.current_password }"
            required />
        </div>

        <div class="grid grid-cols-1">
          <label for="password" class="mb-1 font-semibold">New Password</label>
          <input 
            type="password" 
            id="password"
            v-model="form.password"
            class="p-2 rounded-lg border border-slate-300"
            :class="{ 'border-red-500': form.errors.password }"
            required 
          />
        </div>

        <div class="grid grid-cols-1">
          <label for="password_confirmation" class="mb-1 font-semibold">Confirm Password</label>
          <input 
            type="password" 
            id="password_confirmation"
            v-model="form.password_confirmation"
            class="p-2 rounded-lg border border-slate-300"
            :class="{ 'border-red-500': form.errors.password_confirmation }"
            required 
          />
        </div>

        <div class="mt-6 text-white font-bold text-center">
          <button 
            type="submit"
            class="bg-green-600 hover:bg-green-500 px-8 py-2 rounded-md disabled:opacity-50"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Changing...' : 'Change Password' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" @click="showSuccessModal = false" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div @click.stop class="bg-white p-6 rounded-lg shadow-lg text-center">
        <p class="mb-4 text-green-600 font-semibold">Password changed successfully!</p>
        <p class="text-sm text-gray-600 mb-4">Logging out...</p>
        <button @click="showSuccessModal = false" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
          Close
        </button>
      </div>
    </div>
  </div>
</template>