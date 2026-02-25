<script setup>
import { ref } from 'vue'

const darkMode = ref(false)
const showOverlay = ref(false)

function saveSettings() {
  showOverlay.value = true
}
</script>

<template>
  <div class="max-w-5xl mx-auto p-8">
    <!-- Page Title -->
    <h1 class="text-3xl font-bold mb-8">Settings</h1>

    <!-- Settings Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      <!-- Preferences -->
      <div class="rounded-xl shadow p-6 transition"
           :class="{ 'bg-white': !darkMode, 'bg-gray-800 text-white': darkMode }">

        <div class="flex items-center justify-between">
          <span class="font-medium">{{ darkMode ? 'Dark Mode' : 'Light Mode' }}</span>
          <button
            @click="darkMode = !darkMode"
            class="w-14 h-7 flex items-center bg-gray-300 rounded-full p-1 transition"
            :class="{ 'bg-green-500': darkMode }"   
          >
            <div
              class="bg-white w-5 h-5 rounded-full shadow transform transition"
              :class="{ 'translate-x-7': darkMode }"
            ></div>
          </button>
        </div>
      </div>

    </div>

    <!-- Save Button -->
    <div class="mt-8">
      <button
        @click="saveSettings"
        class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold shadow"
      >
        Save Changes
      </button>

      <!-- Overlay -->
<div
  v-if="showOverlay"
  @click="showOverlay = false"
  class="fixed inset-0 bg-black/50 flex items-center justify-center"
>
  <!-- Modal -->
  <div
    @click.stop
    class="bg-white p-6 rounded-lg shadow-lg text-center"
  >
    <p class="mb-4">Settings saved successfully!</p>

    <button
      @click="showOverlay = false"
      class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
    >
      Close
    </button>
  </div>
</div>

      </div>
  </div>
</template>
