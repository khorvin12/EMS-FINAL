<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    leaves: Array
});

const searchTerm = ref('');

const filteredLeaves = computed(() => {
    if (!searchTerm.value) return props.leaves;
    return props.leaves.filter(leave =>
        leave.id.toString().includes(searchTerm.value)
    );
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

    <div class="flex flex-col px-6">
        <h1 class="text-center text-4xl font-bold mb-12">Your Leave Schedules</h1>

        <div class="flex justify-between mb-6">
            <input type="search" placeholder="Search By Serial No" v-model="searchTerm"
                class="border border-gray-300 rounded-lg px-4 py-2 w-80 focus:outline-none focus:ring-2 focus:ring-blue-400" />

            <Link href="/employee/leaves/create"
                class="font-semibold bg-green-500 hover:bg-green-600 rounded-md px-4 py-2.5">
                Add Leave
            </Link>
        </div>

        <div v-if="!leaves || leaves.length === 0" class="bg-white rounded-lg shadow-lg p-12 text-center">
            <p class="text-gray-500 text-lg mb-4">No leave requests yet</p>
            <Link href="/employee/leaves/create"
                class="bg-green-500 hover:bg-green-600 text-white rounded-md px-6 py-2 inline-block">
                Create Your First Leave Request
            </Link>
        </div>

        <div v-else class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-gray-400 text-black font-medium">
                    <tr>
                        <th>Serial No</th>
                        <th>Reason</th>
                        <th>Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-if="filteredLeaves.length === 0">
                        <td colspan="5" class="p-8 text-center text-gray-500 border-t-4 border-slate-200">
                            No results found
                        </td>
                    </tr>
                    <tr v-else v-for="(leave, index) in filteredLeaves" :key="leave.id"
                        class="border-slate-200 border-t-4">
                        <td>{{ index + 1 }}</td>
                        <td>{{ leave.reason }}</td>
                        <td>{{ leave.start_date }} to {{ leave.end_date }}</td>
                        <td class="text-center">
                            <span :class="getStatusColor(leave.status)"
                                class="inline-block w-24 text-center py-2 rounded-full text-sm font-semibold transition">
                                {{ getStatusText(leave.status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <Link :href="`/employee/leaves/${leave.id}`"
                                class="bg-blue-500 hover:bg-blue-600 inline-flex items-center justify-center w-20 py-2 rounded-md text-sm font-semibold transition">
                                View
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>