<script setup>
import TextInput from '@/Pages/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: null,
    email: null,
    password: null,
});
</script>

<template>
    <form>
        <div class="card grid grid-cols-1 md:grid-cols-2 gap-6">

            <TextInput name="Name" type="text" v-model="form.name" :message="form.errors.name" />

            <TextInput name="Employee ID" type="text" v-model="form.employeeId" :message="form.errors.employeeId" />

            <TextInput name="Gender" type="text" v-model="form.gender" :message="form.errors.gender" />

            <TextInput name="Designation" type="text" v-model="form.designation" :message="form.errors.designation" />

            <TextInput name="Salary" type="text" v-model="form.salary" :message="form.errors.salary" />

            <TextInput name="Role" type="text" v-model="form.role" :message="form.errors.role" />
            
        </div>
    </form>
</template>