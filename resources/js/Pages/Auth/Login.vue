<script setup>

import TextInput from '@/Pages/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';


const form = useForm({
    email: null,
    password: null,
    remember: null,
});

const submit = () => {
    form.post('login');
};
</script>

<template>
    <div>
        <form @submit.prevent="submit">

            <TextInput name="email" type="email" v-model="form.email" :message="form.errors.email" />

            <TextInput name="password" type="password" v-model="form.password" :message="form.errors.password" />

            <div>
                <button class="submit">Login</button>
            </div>

        </form>
    </div>
</template>