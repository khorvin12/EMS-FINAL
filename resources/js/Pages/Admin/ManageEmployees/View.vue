
<template>
    <!-- Contents -->
    <main>
        <!-- Title Contents -->
        <h1 class="text-3xl font-bold mb-10">Addnewemployee</h1>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-48">

            <!-- Total Employees Card -->
            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-green-500 rounded-md px-3 py-3">
                    <i class="fa fa-users fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Total Employees</p>
                    <p class="text-center">4</p>
                </div>
            </div>

            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-green-500 rounded-md px-5 py-3">
                    <i class="fa fa-building fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Total Departments</p>
                    <p class="text-center">4</p>
                </div>
            </div>

            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-green-500 rounded-md px-5 py-3">
                    <i class="fa fa-usd fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Monthly Pay</p>
                    <p class="text-center">4</p>
                </div>
            </div>
        </div>

        <!-- Leave Details -->
        <h2 class="text-2xl font-bold mb-12 text-center">Leave Details</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-yellow-400 rounded-md px-5 py-3">
                    <i class="fa fa-hourglass-half fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Leave Pending</p>
                    <p class="text-center">4</p>
                </div>
            </div>

            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-green-500 rounded-md px-4 py-3">
                    <i class="fa fa-check-circle fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Leave Approved</p>
                    <p class="text-center">4</p>
                </div>
            </div>

            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-red-500 rounded-md px-4 py-3">
                    <i class="fa fa-times-circle fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Leave Rejected</p>
                    <p class="text-center">4</p>
                </div>
            </div>
        </div>
    </main>
</template>
