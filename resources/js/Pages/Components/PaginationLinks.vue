<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

// Inside PaginationLinks.vue
const props = defineProps({
    paginator: {
        type: Object,
        required: true
    }
})
const paginationButtons = computed(() => [
  {
    enabled: !!props.paginator?.prev_page_url,
    href: props.paginator?.prev_page_url,
    label: '‹'
  },
  {
    enabled: !!props.paginator?.next_page_url,
    href: props.paginator?.next_page_url,
    label: '›'
  }
])
</script>

<template>
    <div v-if="paginator" class="flex justify-end items-center mt-4 space-x-2">
      <Link v-if="paginationButtons[0].enabled" :href="paginationButtons[0].href"
        class="w-8 h-8 flex items-center justify-center bg-gray-800 text-white rounded-full hover:bg-gray-700">
        {{ paginationButtons[0].label }}
      </Link>
      <button v-else disabled class="w-8 h-8 flex items-center justify-center bg-gray-300 text-gray-500 rounded-full">
        {{ paginationButtons[0].label }}
      </button>

      <span class="font-semibold">{{ paginator.current_page || 0 }}</span>
      <span class="text-gray-500">of {{ paginator.last_page || 0 }}</span>

      <Link v-if="paginationButtons[1].enabled" :href="paginationButtons[1].href"
        class="w-8 h-8 flex items-center justify-center bg-gray-800 text-white rounded-full hover:bg-gray-700">
        {{ paginationButtons[1].label }}
      </Link>
      <button v-else disabled class="w-8 h-8 flex items-center justify-center bg-gray-300 text-gray-500 rounded-full">
        {{ paginationButtons[1].label }}
      </button>
    </div>
</template>