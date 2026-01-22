<template>
    <div class="flex items-center justify-center h-full">

        <div class="bg-white w-full max-w-lg px-6 py-4 rounded-md shadow-md border-5 border-blue-500">

            <h1 class="text-xl font-bold mb-6 text-center">Add Schedule</h1>

            <div class="grid grid-cols-1 mb-6 gap-1">
                <label for="departmentName">Date</label>
                <input type="text" name="departmentName" placeholder="Select Date"
                    class="p-2 rounded-md border border-gray-300" />
            </div>

            <div class="grid grid-cols-2 gap-1">
                <div>
                    <label for="Time In">Time In</label>
                    <input type="text" name="Time In" placeholder="Time In"
                        class="p-2 rounded-md border border-gray-300" />
                </div>

                <div>
                    <label for="Time Out">Time Out</label>
                    <input type="text" name="Time Out" placeholder="Time Out"
                        class="p-2 rounded-md border border-gray-300" />
                </div>
            </div>

            <div class="grid grid-cols-4 gap-4 mt-6">
                <button class="bg-green-500 hover:bg-green-400 rounded-sm px-2 py-1">Present</button>

                <button class="bg-yellow-400 hover:bg-yellow-300 rounded-sm px-4 py-1">Late</button>

                <button class="bg-red-500 hover:bg-red-400 rounded-sm px-2 py-1">Absent</button>
                
                <button class="bg-blue-400 hover:bg-blue-300 rounded-sm px-2 py-1">On Leave</button>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-6">
                <button class="bg-red-500 hover:bg-red-400 px-4 py-2 rounded-md">Cancel</button>
                <button class="bg-green-500 hover:bg-green-400 px-4 py-2 rounded-md">Confirm</button>
            </div>
        </div>
    </div>
</template>