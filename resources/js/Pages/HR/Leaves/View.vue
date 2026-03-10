<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    leave: Object
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
};

const formatDisplayDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
};

const approve = () => {
    if (confirm('Are you sure you want to approve this leave request?')) {
        router.post(`/hr/leaves/${props.leave.id}/approve`, {}, {
            onSuccess: () => {}
        });
    }
};

const reject = () => {
    if (confirm('Are you sure you want to reject this leave request?')) {
        router.post(`/hr/leaves/${props.leave.id}/reject`, {}, {
            onSuccess: () => {}
        });
    }
};

const goBack = () => {
    router.visit('/hr/leaves');
};

const getStatusColor = (status) => {
    switch(status) {
        case 'approved': return 'bg-green-500';
        case 'rejected': return 'bg-red-500';
        case 'pending': return 'bg-yellow-500';
        default: return 'bg-gray-500';
    }
};
</script>

<template>
    <div class="flex items-center justify-center px-4 py-6">
        <div class="w-full max-w-2xl bg-sky-400 rounded-lg shadow-lg border-4 border-blue-500">

            <div class="flex items-center justify-between px-4 py-3 bg-sky-400 rounded-t-md">
                <h1 class="text-base md:text-xl font-bold text-black pr-4">
                    Leave Request #{{ leave.id }} - {{ leave.user.name }}
                </h1>
                <button @click="goBack" class="text-xl font-bold text-black hover:text-red-600 shrink-0">✕</button>
            </div>

            <div class="m-4 bg-white border-2 border-gray-400 rounded-md p-4 md:p-6">

                <div class="mb-4">
                    <span :class="getStatusColor(leave.status)"
                        class="px-4 py-2 rounded-full text-white font-bold text-sm">
                        STATUS: {{ leave.status.toUpperCase() }}
                    </span>
                </div>

                <div class="mb-4 p-3 bg-gray-50 rounded">
                    <p class="text-sm"><strong>Employee:</strong> {{ leave.user.name }}</p>
                    <p class="text-sm"><strong>Email:</strong> {{ leave.user.email }}</p>
                    <p class="text-sm"><strong>Submitted:</strong> {{ formatDisplayDate(leave.created_at) }}</p>
                </div>

                <!-- From / To -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">From</label>
                        <input type="date" :value="formatDate(leave.start_date)" readonly
                            class="w-full p-2 border border-gray-300 rounded-md bg-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">To</label>
                        <input type="date" :value="formatDate(leave.end_date)" readonly
                            class="w-full p-2 border border-gray-300 rounded-md bg-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                </div>

                <!-- Reason -->
                <div class="mb-4">
                    <label class="mb-1 block text-sm font-medium">Reason</label>
                    <textarea rows="4" :value="leave.reason || 'No reason provided'" readonly
                        class="w-full p-2 border border-gray-300 rounded-md resize-none bg-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

            </div>

            <!-- Approve / Reject -->
            <div class="flex justify-between px-6 md:px-12 pb-6 gap-4">
                <button @click="approve"
                    class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded-md transition">
                    Approve
                </button>
                <button @click="reject"
                    class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold px-4 py-2 rounded-md transition">
                    Reject
                </button>
            </div>

        </div>
    </div>
</template>

<style scoped>
</style>