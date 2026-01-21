<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth?.user ?? null)


</script>

<template>
    <div>
            <h1 class="text-3xl font-bold mb-8">Welcome Employee!</h1>

            <p v-if="user">You are logged in as: {{ user.name }}</p>
            <p v-else>Loading...</p>
    </div>
</template>