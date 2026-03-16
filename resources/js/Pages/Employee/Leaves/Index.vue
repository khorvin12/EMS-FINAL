<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PaginationLinks from '../../Components/PaginationLinks.vue';

const props = defineProps({
    leaves: Object
});

const searchTerm = ref('');

const filteredLeaves = computed(() => {
    const offset = (props.leaves.current_page - 1) * props.leaves.per_page;
    const data = (props.leaves?.data || []).map((leave, index) => ({
        ...leave,
        serialNo: offset + index + 1
    }));
    if (!searchTerm.value) return data;
    return data.filter(leave =>
        leave.serialNo.toString().includes(searchTerm.value)
    );
});

const getStatusColor = (status) => {
    if (!status) return 'bg-gray-400'
    const colors = {
        pending:  'bg-yellow-400',
        rejected: 'bg-red-500',
        approved: 'bg-green-500',
        accepted: 'bg-green-500'
    };
    return colors[status] || 'bg-gray-400';
};

const getStatusText = (status) => {
    if (!status) return 'Unknown'
    return status.charAt(0).toUpperCase() + status.slice(1);
};
</script>

<template>
    <div class="flex flex-col px-6">

        <Head title=" | Manage Leave Request" />

        <h1 class="text-center text-4xl font-bold mb-12">Your Leave Schedules</h1>

        <div class="flex justify-between mb-8 gap-4 whitespace-nowrap">
            <input type="search" placeholder="Search By Serial No" v-model="searchTerm"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />

            <Link href="/employee/leaves/create"
                class="font-semibold bg-green-500 hover:bg-green-600 rounded-md px-4 py-2">
                Add Leave
            </Link>
        </div>

        <div v-if="!filteredLeaves || filteredLeaves.length === 0"
            class="bg-white rounded-lg shadow-lg p-12 text-center whitespace-nowrap">
            <p class="text-gray-500 text-lg mb-4">No leave requests yet</p>
            <Link href="/employee/leaves/create"
                class="bg-green-500 hover:bg-green-600 text-white rounded-md px-6 py-2 inline-block">
                Create Your First Leave Request
            </Link>
        </div>

        <div v-else class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full text-left whitespace-nowrap">
                <thead class="bg-gray-400 text-black font-medium">
                    <tr>
                        <th class="p-4">Serial No</th>
                        <th class="p-4">Reason</th>
                        <th class="p-4">Date</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="leave in filteredLeaves" :key="leave.id" class="border-slate-200 border-t-4">
                        <td class="p-4">{{ leave.serialNo ?? 'N/A' }}</td>
                        <td class="p-4">{{ leave.reason || 'N/A' }}</td>
                        <td class="p-4">{{ leave.start_date }} to {{ leave.end_date }}</td>
                        <td class="p-4 text-center">
                            <span :class="getStatusColor(leave.status)"
                                class="inline-block w-24 text-center py-2 rounded-full text-sm font-semibold transition">
                                {{ getStatusText(leave.status) }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <Link :href="`/employee/leaves/${leave.id}`"
                                class="bg-blue-500 hover:bg-blue-600 text-white inline-flex items-center justify-center w-20 py-2 rounded-md text-sm font-semibold transition">
                                View
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <PaginationLinks :paginator="leaves" />
        </div>
    </div>
</template>