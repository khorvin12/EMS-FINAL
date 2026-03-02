<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: ''
})

const submit = () => {
    // Change this line
    form.post('/hr/reset-password'); 
};
</script>

<template>
    <div class="flex items-center justify-center h-176">
        <div class="bg-white w-full max-w-sm p-6 rounded-md shadow-md border-5 border-blue-500">
            <h1 class="text-xl font-bold mb-6 text-center">Change Password</h1>
            <form @submit.prevent="submit" class="space-y-4">

                <!-- Current Password -->
                <div>
                    <label class="block mb-1">Current Password</label>
                    <input type="password" v-model="form.current_password"
                        class="w-full p-2 rounded-lg border-2 border-slate-300 focus:outline-none focus:border-blue-400" />
                    <div v-if="form.errors.current_password" class="text-red-500 text-sm mt-1">
                        {{ form.errors.current_password }}
                    </div>
                </div>

                <!-- New Password -->
                <div>
                    <label class="block mb-1">New Password</label>
                    <input type="password" v-model="form.password"
                        class="w-full p-2 rounded-lg border-2 border-slate-300 focus:outline-none focus:border-blue-400" />
                    <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                        {{ form.errors.password }}
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block mb-1">Confirm Password</label>
                    <input type="password" v-model="form.password_confirmation"
                        class="w-full p-2 rounded-lg border-2 border-slate-300 focus:outline-none focus:border-blue-400" />
                </div>

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