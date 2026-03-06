<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Reactive data
const stats = ref({
    totalEmployees: 0,
    totalDepartments: 0,
    monthlyPay: 0,
    leavePending: 0,
    leaveApproved: 0,
    leaveRejected: 0
});

// Fetch dashboard stats
const fetchDashboardStats = async () => {
    try {
        const response = await axios.get('/api/dashboard/stats');
        stats.value = response.data;
    } catch (error) {
        console.error('Error fetching dashboard stats:', error);
    }
};

// Load data on component mount
onMounted(() => {
    fetchDashboardStats();
});
</script>

<template>
    <!-- Contents -->
    <div>
        <!-- Title Contents -->
        <h1 class="text-4xl font-bold mb-12">Dashboard Overview</h1>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-48 justify-items-center">

            <!-- Total Employees Card -->
            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-green-500 rounded-md px-3 py-3">
                    <i class="fa fa-users fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Total Employees</p>
                    <p class="text-center">{{ stats.totalEmployees }}</p>
                </div>
            </div>

            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-green-500 rounded-md px-5 py-3">
                    <i class="fa fa-building fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Total Departments</p>
                    <p class="text-center">{{ stats.totalDepartments }}</p>
                </div>
            </div>
        </div>

        <!-- Leave Details -->
        <h2 class="text-3xl font-bold mb-12 text-center">Leave Details</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center">

            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-yellow-400 rounded-md px-5 py-3">
                    <i class="fa fa-hourglass-half fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Leave Pending</p>
                    <p class="text-center">{{ stats.leavePending }}</p>
                </div>
            </div>

            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-green-500 rounded-md px-4 py-3">
                    <i class="fa fa-check-circle fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Leave Approved</p>
                    <p class="text-center">{{ stats.leaveApproved }}</p>
                </div>
            </div>

            <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
                <div class="bg-red-500 rounded-md px-4 py-3">
                    <i class="fa fa-times-circle fa-2x" />
                </div>
                <div>
                    <p class="text-lg font-medium">Leave Rejected</p>
                    <p class="text-center">{{ stats.leaveRejected }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

