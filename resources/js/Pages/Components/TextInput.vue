<script setup>

const model = defineModel({
    type: null,
    required: true
});

console.log(model)


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
        <label class="mb-1 font-semibold">{{ name }}</label>
        <input :type="type" v-model="model" :class="{ '!ring-red-500': message }" :placeholder="`Enter ${name}`"
            class="bg-white w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-400" />
        <small class="error">{{ message }}</small>
    </div>
</template>