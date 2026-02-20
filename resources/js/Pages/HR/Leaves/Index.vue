<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// Receive data from controller
const props = defineProps({
    leaves: Object,
    stats: Object
});

// Search and filter state
const searchQuery = ref('');
const dateFilter = ref('');

// Filtered leaves based on search and date
const filteredLeaves = computed(() => {
    let result = props.leaves?.data || [];

    // Search by employee name or ID
    if (searchQuery.value) {
        result = result.filter(leave =>
            leave.user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            leave.id.toString().includes(searchQuery.value)
        );
    }

    // Filter by date
    if (dateFilter.value) {
        result = result.filter(leave =>
            leave.start_date <= dateFilter.value && leave.end_date >= dateFilter.value
        );
    }

    return result;
});

// Format date helper
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

// Status badge color
const getStatusColor = (status) => {
    // Convert to lowercase to ensure it matches regardless of database formatting
    const s = status?.toLowerCase(); 
    
    switch (s) {
        case 'approved': return 'bg-green-500 text-black';
        case 'rejected': return 'bg-red-500 text-black';
        case 'pending':  return 'bg-yellow-500 text-black';
        default:         return 'bg-gray-500 text-black';
    }
};

const TableColumns = [
    { name: 'Serial No.', key: 'serial' },
    { name: 'Employee ID', key: 'employee_id' },
    { name: 'Name', key: 'name' },
    { name: 'Start Date', key: 'start_date' },
    { name: 'End Date', key: 'end_date' },
    { name: 'Reason', key: 'reason' },
    { name: 'Status', key: 'status', align: 'center' },
    { name: 'Action', key: 'action', align: 'center' }
];

const LeaveDataTable = computed(() => {
    return filteredLeaves.value.map((leave, index) => ({
        serial: index + 1,
        employee_id: leave.user?.id || 'N/A',
        name: leave.user?.name || 'Unknown',
        start_date: formatDate(leave.start_date),
        end_date: formatDate(leave.end_date),
        reason: leave.reason,
        status: leave.status,
        id: leave.id
    }));
});
</script>

<template>
    <div class="flex flex-col">
        <div class="max-w-8xl mx-auto">
            <!-- Content Title -->
            <h1 class="text-center text-3xl font-bold mb-12">Manage Leave Request</h1>

            <div class="flex items-center justify-between mb-6">
                <!-- Search -->
                <input v-model="searchQuery" type="search" placeholder="Search By Name or ID"
                    class="bg-white rounded-md p-2 border border-gray-300 focus:outline-none focus:border-blue-400" />

                <!-- Date Filter -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium">Filter by Date</label>
                    <input v-model="dateFilter" type="date" name="date"
                        class="bg-white rounded-md p-2 border border-gray-300 focus:outline-none focus:border-blue-400">
                </div>
            </div>

            <!-- Stats Summary (Optional) -->
            <div v-if="stats" class="grid grid-cols-4 gap-4 mb-6">

                <div class="bg-white rounded-lg p-4 shadow">
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-2xl font-bold">{{ stats.all }}</p>
                </div>

                <div class="bg-yellow-100 rounded-lg p-4 shadow">
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold">{{ stats.pending }}</p>
                </div>

                <div class="bg-green-100 rounded-lg p-4 shadow">
                    <p class="text-sm text-gray-600">Approved</p>
                    <p class="text-2xl font-bold">{{ stats.approved }}</p>
                </div>

                <div class="bg-red-100 rounded-lg p-4 shadow">
                    <p class="text-sm text-gray-600">Rejected</p>
                    <p class="text-2xl font-bold">{{ stats.rejected }}</p>
                </div>

            </div>

            <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg text-left">
                
                <thead>
                    <tr class="bg-gray-400 text-black">
                        <th v-for="column in TableColumns" :key="column.key" :class="[
                            'p-6',
                            column.align === 'center' ? 'text-center' : ''
                        ]">
                            {{ column.name }}
                        </th>
                    </tr>
                </thead>

                <tbody>

                    <!-- Show message if no leaves found -->
                    <tr v-if="!filteredLeaves || filteredLeaves.length === 0">
                        <td colspan="8" class="text-center p-8 text-gray-500">
                            No leave requests found
                        </td>
                    </tr>

                    <!-- Display each leave request -->
                    <tr v-for="(row, index) in LeaveDataTable" :key="row.id"
                        class="bg-white text-left border-slate-200 border-t-4">

                        <td class="px-6 py-4">{{ index + 1 }}</td>
                        <td class="px-6 py-4">{{ row.user?.id || 'N/A' }}</td>
                        <td class="px-6 py-4">{{ row.user?.name || 'Unknown' }}</td>
                        <td class="px-6 py-4">{{ formatDate(row.start_date) }}</td>
                        <td class="px-6 py-4">{{ formatDate(row.end_date) }}</td>
                        <td class="px-6 py-4">{{ row.reason }}</td>
                        <td class="px-6 py-4 text-center">
                            <span :class="getStatusColor(row.status)"
                                class="px-4 py-2 rounded-full text-sm font-semibold inline-block text-black">
                                {{ row.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <Link :href="`/hr/leaves/review/${row.id}`"
                                class="bg-blue-500 hover:bg-blue-600 rounded-lg px-4 py-2 text-sm font-semibold">
                                View
                            </Link>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>