<template>
    <div class="flex items-center justify-center h-176">
        <div class="bg-white w-full max-w-sm px-6 py-4 rounded-md shadow-md border-5 border-green-600">
            <h1 class="text-xl font-bold mb-6 text-center">Change Password</h1>

            <div class="grid grid-cols-1 mb-6 gap-6 p-6">
                <div class="grid grid-cols-1">
                    <label for="password" class="mb-1">New Password</label>
                    <input type="password" name="password"
                        class="p-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-400" />
                </div>

                <div class="grid grid-cols-1">
                    <label for="password_confirmation" class="mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="p-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-400" />
                </div>
            </div>

            <div class="mt-6 text-white font-bold text-center">
                <button class="bg-green-500 hover:bg-green-600 px-8 py-2 rounded-md">Change Password</button>
            </div>
        </div>
    </div>
</template>