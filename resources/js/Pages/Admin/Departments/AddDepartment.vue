<template>
    <div class="flex items-center justify-center h-full">
        <div class="bg-white w-full max-w-sm px-6 py-4 rounded-md shadow-md border-5 border-green-600">
            <h1 class="text-xl font-bold mb-6 text-center">Add New Department</h1>

            <div class="grid grid-cols-1 mb-6 space-y-1">
                <label for="departmentName">Department Name</label>
                <input type="text" name="departmentName" id="departmentName" placeholder="Department Name"
                    class="p-2 rounded-md border border-gray-300" />
            </div>

            <div class="grid grid-cols-1 space-y-1">
                <label for="description">Description</label>
                <textarea name="description" rows="5" id="description" placeholder="Description"
                    class="p-2 rounded-md border border-gray-300"></textarea>
            </div>

            <div class="mt-6 text-white font-bold text-center">
                <button class="bg-green-500 hover:bg-green-600 px-8 py-2 rounded-md">Add Department</button>
            </div>
        </div>
    </div>
</template>