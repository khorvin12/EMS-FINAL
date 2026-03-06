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
    <div class="flex items-center justify-center h-full bg-gray-100">
        <div class="w-full max-w-2xl bg-sky-400 rounded-lg shadow-lg border-4 border-blue-500">

            <div class="flex items-center justify-between px-4 py-3 bg-sky-400 rounded-t-md">
                <h1 class="text-xl font-bold text-black">
                    Leave Request #{{ leave.id }} - {{ leave.user.name }}
                </h1>
                <button @click="goBack" class="text-xl font-bold text-black hover:text-red-600">✕</button>
            </div>

            <div class="m-4 bg-white border-2 border-gray-400 rounded-md p-6">

                <div class="mb-4">
                    <span 
                        :class="getStatusColor(leave.status)"
                        class="px-4 py-2 rounded-full text-white font-bold text-sm"
                    >
                        STATUS: {{ leave.status.toUpperCase() }}
                    </span>
                </div>

                <div class="mb-4 p-3 bg-gray-50 rounded">
                    <p class="text-sm"><strong>Employee:</strong> {{ leave.user.name }}</p>
                    <p class="text-sm"><strong>Email:</strong> {{ leave.user.email }}</p>
                    <p class="text-sm"><strong>Submitted:</strong> {{ formatDisplayDate(leave.created_at) }}</p>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">From</label>
                        <input type="date" :value="formatDate(leave.start_date)" readonly
                            class="p-2 border border-gray-500 rounded-md bg-gray-100" />
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">To</label>
                        <input type="date" :value="formatDate(leave.end_date)" readonly
                            class="p-2 border border-gray-500 rounded-md bg-gray-100" />
                    </div>
                </div>

                <div class="mb-4">
                    <label class="mb-1 block text-sm font-medium">Reason</label>
                    <input type="text" :value="leave.reason" readonly
                        class="w-full p-2 border border-gray-500 rounded-md font-semibold bg-gray-100" />
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Reason</label>
                    <textarea rows="6" :value="leave.reason || 'No reason provided'" readonly
                        class="w-full p-2 border border-gray-500 rounded-md resize-none bg-gray-100"></textarea>
                </div>
            </div>

            <!-- Always show Approve and Reject regardless of current status -->
            <div class="flex justify-between px-12 pb-6">
                <button
                    @click="approve"
                    class="bg-green-500 hover:bg-green-400 text-white font-bold px-6 py-2 rounded-md border border-black transition"
                >
                    Approve
                </button>

                <button
                    @click="reject"
                    class="bg-red-500 hover:bg-red-400 text-white font-bold px-6 py-2 rounded-md border border-black transition"
                >
                    Reject
                </button>
            </div>

        </div>
    </div>
</template>

<style scoped>
</style>