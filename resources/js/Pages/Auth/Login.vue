<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import TextInput from '../Components/TextInput.vue';

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const showPassword = ref(false)

function handleLogin() {
  form.post('/login', {
    onError: () => {
      form.reset('password')
    }
  })
}
</script>

<script>
export default {
  layout: null,
}
</script>

<template>
  <Transition name="fade" appear>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-r from-green-200 to-blue-200 font-sans">
      <h1 class="text-4xl font-bold text-blue-700 mb-8 drop-shadow">
        EMPLOYEE MANAGEMENT
      </h1>

      <div class="bg-gradient-to-r from-green-400 to-lime-300 p-6 rounded-lg w-80 shadow-md">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Login</h2>

        <form @submit.prevent="handleLogin">

          <TextInput name="Email" v-model="form.email" :message="form.errors.email" class="mb-4" />

          <div class="mb-6">
            <TextInput name="Password" :type="showPassword ? 'text' : 'password'" v-model="form.password"
              :message="form.errors.password" />

            <label class="flex items-center gap-2 mt-1 text-sm text-gray-700 cursor-pointer">
              <input type="checkbox" v-model="showPassword" />
              Show Password
            </label>
          </div>

          <button type="submit" :disabled="form.processing"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold transition flex items-center justify-center gap-2 disabled:opacity-60">
            <i v-if="form.processing" class="fa-solid fa-spinner fa-spin text-sm"></i>
            <span>{{ form.processing ? 'Logging in...' : 'Login' }}</span>
          </button>

        </form>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
.fade-enter-active { transition: opacity 0.4s ease, transform 0.4s ease; }
.fade-enter-from   { opacity: 0; transform: translateY(16px); }
</style>