<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    leave: Object
});

const getStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-400',
        rejected: 'bg-red-500',
        approved: 'bg-green-500',
        accepted: 'bg-green-500'
    };
    return colors[status] || 'bg-gray-400';
};

const getStatusText = (status) => {
    return status.charAt(0).toUpperCase() + status.slice(1);
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 p-6">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Leave Details</h2>
                <Link href="/employee/leaves" class="text-2xl font-bold hover:text-red-600 transition">
                    ×
                </Link>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Leave ID</label>
                        <p class="text-lg">#{{ leave.id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span :class="getStatusColor(leave.status)" class="text-white px-3 py-1 rounded text-sm">
                            {{ getStatusText(leave.status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <p class="text-lg">{{ leave.start_date }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <p class="text-lg">{{ leave.end_date }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                    <p class="text-lg font-semibold">{{ leave.reason }}</p>
                </div>

                <div v-if="leave.description">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <p class="text-base bg-gray-50 p-3 rounded border">{{ leave.description }}</p>
                </div>

                <div v-if="leave.admin_comment">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Admin Comment</label>
                    <p class="text-base bg-yellow-50 p-3 rounded border border-yellow-200">{{ leave.admin_comment }}</p>
                </div>
            </div>

            <div class="mt-6">
                <Link href="/employee/leaves" 
                      class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded shadow">
                    Back to List
                </Link>
            </div>
        </div>
    </div>
</template>