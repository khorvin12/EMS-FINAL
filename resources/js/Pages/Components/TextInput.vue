<script setup>

const model = defineModel({
    type: null,
    required: true
});

console.log(model)


defineProps({
   name:  {
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
    <div class="">
        <label>{{ name }}</label>
        <input :type="type" v-model="model" />
        <small class="error">{{ message }}</small>
    </div>
</template>