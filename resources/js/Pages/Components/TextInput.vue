<script setup>

const model = defineModel({
    type: null,
    required: true
});

defineProps({
    name: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        default: 'text',
    },
    message: String
});

</script>

<template>
    <div>
        <label class="block mb-1 text-sm font-semibold text-gray-700">{{ name }}</label>
        <input :type="type" v-model="model" :class="{ '!border-red-500 !ring-red-500': message }"
            :placeholder="`Enter ${name}`"
            class="bg-white w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition" />
        <small v-if="message" class="error">{{ message }}</small>
    </div>
</template>