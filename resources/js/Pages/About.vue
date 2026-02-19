<script setup>
defineProps({
    user: String
})
</script>

<template>
    <div>
        <h1>About Page {{ user }}</h1>
    </div>
</template>