<script setup>
import { useForm } from '@inertiajs/vue3'
import TextInput from '../../Components/TextInput.vue';

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: ''
})

const submit = () => {
    // Change this line
    form.post('/employee/reset-password');
};
</script>

<template>
    <div class="flex items-center justify-center py-36">
        <div class="bg-white w-full max-w-sm p-6 rounded-md shadow-md border-4 border-green-500">
            
            <h1 class="text-xl font-bold mb-6 text-center">Change Password</h1>

            <form @submit.prevent="submit" class="space-y-4">

                <!-- Current Password -->
                <TextInput name="Old Password" type="password" v-model="form.current_password"
                    :message="form.errors.current_password" />

                <!-- New Password -->
                <TextInput name="New Password" type="password" v-model="form.password" :message="form.errors.password" />

                <!-- Confirm Password -->
                <TextInput name="Confirm Password" type="password" v-model="form.password_confirmation" />

                <!-- Button -->
                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-500 text-white font-bold py-2 rounded-md mt-4"
                    :disabled="form.processing">
                    Change Password
                </button>

            </form>
        </div>
    </div>
</template>