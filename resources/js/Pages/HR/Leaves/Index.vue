<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// Receive data from controller
const props = defineProps({
    leaves: Array,
    stats: Object
});

// Search and filter state
const searchQuery = ref('');
const dateFilter = ref('');

// Filtered leaves based on search and date
const filteredLeaves = computed(() => {
    let result = props.leaves || [];
    
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
    switch(status) {
        case 'approved': return 'bg-green-500 text-white';
        case 'rejected': return 'bg-red-500 text-white';
        case 'pending': return 'bg-yellow-500 text-white';
        default: return 'bg-gray-500 text-white';
    }
};
</script>

<template>
    <!-- Content Title -->
    <h1 class="text-center text-3xl font-bold mb-12">Manage Leave Request</h1>

    <div class="flex items-center justify-between mb-6">
        <!-- Search -->
        <input 
            v-model="searchQuery"
            type="search" 
            placeholder="Search By Name or ID" 
            class="bg-white rounded-md p-2 border border-gray-300" 
        />

        <!-- Date Filter -->
        <div class="flex items-center gap-2">
            <label class="text-sm font-medium">Filter by Date</label>
            <input 
                v-model="dateFilter"
                type="date" 
                name="date" 
                class="bg-white rounded-md p-2 border border-gray-300"
            >
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

    <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
        <thead>
            <tr class="bg-gray-400 text-black font-medium text-lg">
                <th class="p-6">Serial No.</th>
                <th class="p-6">Employee ID</th>
                <th class="p-6">Name</th>
                <th class="p-6">Start Date</th>
                <th class="p-6">End Date</th>
                <th class="p-6">Reason</th>
                <th class="p-6">Status</th>
                <th class="p-6">Action</th>
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
            <tr 
                v-for="(leave, index) in filteredLeaves" 
                :key="leave.id"
                class="bg-white-100 text-center border-slate-200 border-t-4"
            >
                <td class="p-4">{{ index + 1 }}</td>
                <td class="p-4">{{ leave.user.id || 'N/A' }}</td>
                <td class="p-4">{{ leave.user.name }}</td>
                <td class="p-4">{{ formatDate(leave.start_date) }}</td>
                <td class="p-4">{{ formatDate(leave.end_date) }}</td>
                <td class="p-4">{{ leave.reason }}</td>
                <td class="p-4">
                    <span 
                        :class="getStatusColor(leave.status)"
                        class="px-3 py-1 rounded-full text-sm font-semibold"
                    >
                        {{ leave.status.toUpperCase() }}
                    </span>
                </td>
                <td class="p-4 space-x-4 inline-flex">
                    <Link 
                        :href="`/hr/leaves/review/${leave.id}`" 
                        class="bg-blue-500 hover:bg-blue-400 rounded-sm px-4 py-1 text-white"
                    >
                        View
                    </Link>
                </td>
            </tr>
        </tbody>
    </table>
</template>