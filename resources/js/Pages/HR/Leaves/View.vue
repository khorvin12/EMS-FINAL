<script setup>
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

// Receive leave data from controller
const props = defineProps({
    leave: Object
});

// Format date helper
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toISOString().split('T')[0]; // Returns YYYY-MM-DD format
};

const formatDisplayDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

// Handle approve action
const approve = () => {
    if (confirm('Are you sure you want to approve this leave request?')) {
        router.post(`/hr/leaves/${props.leave.id}/approve`, {}, {
            onSuccess: () => {
                // Will redirect to /hr/leaves automatically
            }
        });
    }
};

// Handle reject action
const reject = () => {
    if (confirm('Are you sure you want to reject this leave request?')) {
        router.post(`/hr/leaves/${props.leave.id}/reject`, {}, {
            onSuccess: () => {
                // Will redirect to /hr/leaves automatically
            }
        });
    }
};

// Go back to list
const goBack = () => {
    router.visit('/hr/leaves');
};

// Status badge color
const getStatusColor = (status) => {
    // Convert to lowercase to ensure it matches regardless of database formatting
    const s = status?.toLowerCase();

    switch (s) {
        case 'approved': return 'bg-green-500 text-black';
        case 'rejected': return 'bg-red-500 text-black';
        case 'pending': return 'bg-yellow-500 text-black';
        default: return 'bg-gray-500 text-black';
    }
};
</script>

<template>
    <div class="flex items-center justify-center h-182 bg-gray-100">

        <!-- OUTER MODAL -->
        <div class="w-full max-w-4xl bg-sky-400 rounded-lg shadow-lg border-4 border-blue-500">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-4 py-3 bg-sky-400 rounded-t-md">
                <h1 class="text-xl font-bold text-black">
                    Leave Request #{{ leave.id }} - {{ leave.user.name }}
                </h1>

                <button @click="goBack" class="text-xl font-bold text-black hover:text-red-600">
                    ✕
                </button>
            </div>

            <!-- INNER CONTENT -->
            <div class="m-4 bg-white border-2 border-gray-400 rounded-md p-6">

                <!-- Status Badge -->
                <div class="mb-4">
                    <span :class="getStatusColor(leave.status)"
                        class="px-4 py-2 rounded-full text-white font-bold text-sm">
                        STATUS: {{ leave.status.toUpperCase() }}
                    </span>
                </div>

                <!-- Employee Info -->
                <div class="mb-4 p-3 bg-gray-50 rounded">
                    <p class="text-sm"><strong>Employee:</strong> {{ leave.user.name }}</p>
                    <p class="text-sm"><strong>Email:</strong> {{ leave.user.email }}</p>
                    <p class="text-sm"><strong>Submitted:</strong> {{ formatDisplayDate(leave.created_at) }}</p>
                </div>

                <!-- DATE RANGE -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">From</label>
                        <input type="date" :value="formatDate(leave.start_date)" readonly
                            class="p-2 border border-gray-500 rounded-md bg-gray-100 focus:outline-none focus:border-blue-400" />
                    </div>

                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">To</label>
                        <input type="date" :value="formatDate(leave.end_date)" readonly
                            class="p-2 border border-gray-500 rounded-md bg-gray-100 focus:outline-none focus:border-blue-400" />
                    </div>
                </div>
                <!-- Reason -->
<div>
    <label class="mb-1 block text-sm font-medium">Reason</label>
    <textarea rows="4" :value="leave.reason || 'No Reason provided'" readonly
        class="w-full p-2 border border-gray-500 rounded-md resize-none bg-gray-100 focus:outline-none focus:border-blue-400 font-semibold"></textarea>
</div>
            </div>

            <!-- FOOTER BUTTONS -->
            <div class="flex justify-between px-12 pb-6">
                <!-- Only show approve/reject buttons if status is pending -->
                <template v-if="leave.status === 'pending'">
                    <button @click="approve"
                        class="bg-green-500 hover:bg-green-400 text-white font-bold px-6 py-2 rounded-md border border-black transition">
                        Approve
                    </button>

                    <button @click="reject"
                        class="bg-red-500 hover:bg-red-400 text-white font-bold px-6 py-2 rounded-md border border-black transition">
                        Reject
                    </button>
                </template>

                <!-- If already approved/rejected, show status message -->
                <template v-else>
                    <div class="w-full text-center">
                        <p class="text-lg font-semibold">
                            This leave request has been {{ leave.status }}
                        </p>
                        <button @click="goBack"
                            class="mt-4 bg-blue-500 hover:bg-blue-400 text-white font-bold px-6 py-2 rounded-md">
                            Back to List
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>