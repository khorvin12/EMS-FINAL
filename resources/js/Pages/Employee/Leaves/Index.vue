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
    <div class="min-h-screen bg-gray-100 p-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-center text-3xl font-bold mb-12">Your Leave Schedules</h1>

            <div class="flex justify-between mb-6">
                <div class="bg-white rounded-md p-2">
                    <input
                        type="search"
                        placeholder="Search By SNO"
                        v-model="searchTerm"
                        class="px-2 outline-none"
                    />
                </div>

                <Link href="/employee/leaves/create" class="bg-green-500 hover:bg-green-600 text-white rounded-md px-4 py-2">
                    Add Leave
                </Link>
            </div>

            <div v-if="!leaves || leaves.length === 0" class="bg-white rounded-lg shadow-lg p-12 text-center">
                <p class="text-gray-500 text-lg mb-4">No leave requests yet</p>
                <Link href="/employee/leaves/create" class="bg-green-500 hover:bg-green-600 text-white rounded-md px-6 py-2 inline-block">
                    Create Your First Leave Request
                </Link>
            </div>

            <table v-else class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
                <thead class="border-slate-200 text-slate-600">
                    <tr class="bg-gray-400 text-black font-medium">
                        <th class="p-6">SNO</th>
                        <th>Reason</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-if="filteredLeaves.length === 0">
                        <td colspan="5" class="p-8 text-center text-gray-500">
                            No results found
                        </td>
                    </tr>
                    <tr v-else v-for="(leave, index) in filteredLeaves" :key="leave.id" 
                        class="bg-white text-center border-slate-200 border-t-4 hover:bg-gray-50">
                        <td class="p-4">{{ index + 1 }}</td>
                        <td class="p-4">{{ leave.reason }}</td>
                        <td class="p-4">{{ leave.start_date }} to {{ leave.end_date }}</td>
                        <td class="p-4">
                            <span :class="getStatusColor(leave.status)" class="text-white rounded-sm px-2 py-1">
                                {{ getStatusText(leave.status) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <Link :href="`/employee/leaves/${leave.id}`" 
                                  class="bg-blue-400 hover:bg-blue-500 text-white rounded-sm px-2 py-1">
                                View
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>