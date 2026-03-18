<script setup>
import { Head } from '@inertiajs/vue3';

// Reactive data
const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            totalEmployees: 0,
            totalDepartments: 0,
            monthlyPay: 0,
            leavePending: 0,
            leaveApproved: 0,
            leaveRejected: 0
        })
    }
})
</script>

<template>
    <!-- Contents -->
    <div class="flex flex-col px-6">

        <Head title=" | Dashboard" />

        <!-- Title Contents -->
        <div class="mb-6 md:mb-12">
            <h1 class="text-2xl md:text-4xl font-bold">Dashboard Overview</h1>
            <p class="text-sm text-gray-500 mt-1">Employee Summary and Leave Details</p>
        </div>

        <h2 class="text-3xl font-bold mb-8 text-center">Employee Summary</h2>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-36 justify-items-center">

            <!-- Total Employees Card -->
            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-green-500 rounded-l-md px-5 py-5 shrink-0 self-stretch flex items-center">
                    <i class="fa fa-users fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Total Employees</p>
                    <p class="text-center">{{ props.stats.totalEmployees }}</p>
                </div>
            </div>

            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-green-500 rounded-l-md px-5 py-5 shrink-0 self-stretch flex items-center">
                    <i class="fa fa-building fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Total Departments</p>
                    <p class="text-center">{{ props.stats.totalDepartments }}</p>
                </div>
            </div>
        </div>

        <!-- Leave Details -->
        <h2 class="text-3xl font-bold mb-8 text-center">Leave Details</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center">

            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-yellow-400 rounded-l-md px-5 py-5 shrink-0 self-stretch flex items-center">
                    <i class="fa fa-hourglass-half fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Leave Pending</p>
                    <p class="text-center">{{ props.stats.leavePending }}</p>
                </div>
            </div>

            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-green-500 rounded-l-md px-5 py-5 shrink-0 self-stretch flex items-center">
                    <i class="fa fa-check-circle fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Leave Approved</p>
                    <p class="text-center">{{ props.stats.leaveApproved }}</p>
                </div>
            </div>

            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-red-500 rounded-l-md px-5 py-5 shrink-0 self-stretch flex items-center">
                    <i class="fa fa-times-circle fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Leave Rejected</p>
                    <p class="text-center">{{ props.stats.leaveRejected }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
