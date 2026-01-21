<template>
    <div class="flex items-center justify-center h-full">
        <div class="bg-white w-full max-w-lg px-6 py-4 rounded-md shadow-md border-5 border-green-600">
            <h1 class="text-xl font-bold mb-6 text-center">Request for Leave</h1>

            <div class="grid grid-cols-2 mb-6 gap-4">
                <div>
                    <label for="departmentName" class="mb-1">From</label>
                    <input type="text" name="departmentName" id="departmentName" placeholder="Select Date"
                        class="p-2 rounded-md border border-gray-300" />
                </div>

                <div class="">
                    <label for="departmentName" class="mb-1">To</label>
                    <input type="text" name="departmentName" id="departmentName" placeholder="Select Date"
                        class="p-2 rounded-md border border-gray-300" />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-1">
                <label for="description">Reason</label>
                <input type="text" name="departmentName" id="departmentName" placeholder="Title"
                    class="p-2 rounded-md border border-gray-300" />
                <textarea name="description" rows="6" id="description" placeholder="Description"
                    class="p-2 rounded-md border border-gray-300"></textarea>
            </div>

            <div class="mt-6 text-white font-bold text-center space-x-4">
                <button class="bg-green-600 hover:bg-green-500 px-8 py-2 rounded-md">Cancel Leave</button>
                <button class="bg-green-600 hover:bg-green-500 px-8 py-2 rounded-md">Submit Leave</button>
            </div>
        </div>
    </div>
</template>