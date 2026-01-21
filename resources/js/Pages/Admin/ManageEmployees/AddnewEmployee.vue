<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
  birth: '',
  department: '',
})

const submit = () => form.post('/employees')
</script>

<template>
  <div class="p-8 max-w-xl mx-auto bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-6">Add New Employee</h1>

    <form @submit.prevent="submit" class="space-y-4">

      <!-- Birth Date -->
      <div>
        <label class="block mb-1 font-semibold">Birth Date</label>
        <input
          type="date"
          v-model="form.birth"
          class="w-full px-4 py-2 border rounded"
        />
        <div v-if="form.errors.birth" class="text-red-500 text-sm">{{ form.errors.birth }}</div>
      </div>

      <!-- Department -->
      <div>
        <label class="block mb-1 font-semibold">Department</label>
        <input
          type="text"
          v-model="form.department"
          class="w-full px-4 py-2 border rounded"
        />
        <div v-if="form.errors.department" class="text-red-500 text-sm">{{ form.errors.department }}</div>
      </div>

      <!-- Buttons -->
      <div class="flex justify-between">
        <Link href="/manageemployee" class="text-gray-600 hover:underline">Cancel</Link>
        <button
          type="submit"
          class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded"
          :disabled="form.processing"
        >
          Save
        </button>
      </div>

    </form>
  </div>
</template>
