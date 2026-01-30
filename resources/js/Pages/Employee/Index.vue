<template>
    <div class="space-y-10">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Welcome back, Khorvin!</h2>
            <p class="text-gray-600">Here is what's happening with your profile today.</p>
        </div>

        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-16 overflow-hidden mb-16">

            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                <p class="text-xs text-gray-500 uppercase font-bold text-center tracking-tight">Present Days This Month
                </p>
                <p class="text-3xl font-bold text-center mt-2">10</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
                <p class="text-xs text-gray-500 uppercase font-bold text-center tracking-tight">Pending Requests</p>
                <p class="text-3xl font-bold text-center mt-2">2</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                <p class="text-xs text-gray-500 uppercase font-bold text-center tracking-tight">Estimated Salary</p>
                <p class="text-3xl font-bold text-gray-800 mt-2 text-center">₱25,400.00</p>
            </div>

        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm overflow-hidden">

            <h3 class="font-bold border-b pb-3 mb-4 text-2xl">Recent Attendance</h3>

            <ul class="space-y-6">
                <li class="flex justify-between text-xl">
                    <span>Jan 30, 2026</span>
                    <span class="text-green-600 font-medium">Present (08:55 AM)</span>
                </li>

                <li class="flex justify-between text-xl">
                    <span>Jan 29, 2026</span>
                    <span class="text-green-600 font-medium">Present (09:02 AM)</span>
                </li>
            </ul>
        </div>
    </div>
</template>