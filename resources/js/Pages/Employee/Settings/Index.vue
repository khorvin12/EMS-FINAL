<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Employee Settings</h1>
        <div class="flex items-center justify-center">
            <div class="flex flex-col bg-gray-200 rounded-lg p-10 shadow-lg w-full max-w-2xl">
                <Link href="/employee/changepass" class="block bg-white rounded-md px-6 py-4 shadow hover:bg-gray-50 cursor-pointer transition">
                    <h1 class="text-black text-left font-semibold">Change Password</h1>
                </Link>
                <Link href="/employee/settings/profile" class="block bg-white rounded-md px-6 py-4 shadow hover:bg-gray-50 cursor-pointer transition mt-4">
                    <h1 class="text-black text-left font-semibold">Profile Settings</h1>
                </Link>
            </div>
        </div>
    </div>
</template>