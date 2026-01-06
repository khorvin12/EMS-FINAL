<script setup>
import { Link } from '@inertiajs/vue3';
import TextInput from '@/Pages/Components/TextInput.vue';
</script>

<template>
    <h1 class="text-center">Manage Employees</h1>

    <div class="flex justify-between mb-6">
        <div>
            <TextInput label="Search" name="search" id="search" />
        </div>

        <div>
            <Link href="">Add New Employee</Link>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-12 mb-12 ">
        <TextInput label="ID" name="ID" id="ID" />
        <TextInput label="Firstname" name="Firstname" id="Firstname" />
        <TextInput label="Lastname" name="Lastname" id="Lastname" />
        <TextInput label="Department" name="Department" id="Department" />
        <TextInput label="Role" name="Role" id="Role" />
    </div>

</template>