<template>
    <div class="flex items-center justify-center h-full">
        <div class="bg-white w-full max-w-md px-6 py-4 rounded-md shadow-md border-5 border-blue-500">
            <h1 class="text-xl font-bold mb-6 text-center">Your Leave Details</h1>

            <div class="grid grid-cols-1 mb-2">
                <label for="departmentName">Reason</label>
                <input type="text" name="departmentName" id="departmentName" placeholder="Title"
                    class="p-2 rounded-md border border-gray-300" />
            </div>

            <div class="grid grid-cols-1 mb-2">
                <label for="description">Description</label>
                <textarea name="description" rows="8" id="description" placeholder="Description"
                    class="p-2 rounded-md border border-gray-300"></textarea>
            </div>

            <p>Date Created: 1/30/2026</p>
        </div>
    </div>
</template>