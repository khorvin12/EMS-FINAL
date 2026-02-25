<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import PaginationLinks from '../../Components/PaginationLinks.vue';

const props = defineProps({
    leaves: Object
});

const searchTerm = ref('');

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

const TableColumns = [
    { label: 'Serial No', key: 'id', align: 'left' },
    { label: 'Reason', key: 'reason', align: 'left' },
    { label: 'Date', key: 'date', align: 'left' },
    { label: 'Status', key: 'status', align: 'center' },
    { label: 'Action', key: 'action', align: 'center' }
]

const LeaveDataTable = computed(() => {
    let data = props.leaves.data ?? [];

    // Search Filter
    if (searchTerm.value) {
        data = data.filter(leave =>
            leave.id.toString().includes(searchTerm.value)
        );
    }

    // Map for Table Display
    return data.map(leave => ({
        id: leave.id,
        reason: leave.reason,
        date: `${leave.start_date} to ${leave.end_date}`,
        status: leave.status
    }));
});
</script>

<template>
    <div class="flex flex-col p-6">
        <div class="max-w-8xl mx-auto">
            <h1 class="text-center text-3xl font-bold mb-12">Leave Details</h1>

            <div class="flex justify-between mb-6">

                <input type="search" id="search" name="search" placeholder="Search By Serial No" v-model="searchTerm"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />

                <Link href="/employee/leaves/create"
                    class="bg-green-500 hover:bg-green-600 rounded-md px-4 py-2 font-semibold">
                    Add Leave
                </Link>

            </div>

            <div v-if="!leaves.data || leaves.data.length === 0" class="bg-white rounded-lg shadow-lg p-12 text-center min-w-7xl mx-auto">

                <p class="text-gray-500 text-lg mb-6">No leave requests yet</p>

                <Link href="/employee/leaves/create"
                    class="bg-green-500 hover:bg-green-600 rounded-md px-6 py-2 inline-block">
                    Create Your First Leave Request
                </Link>

            </div>

            <table v-else class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg text-left">
                <thead class="border-slate-200 text-slate-600">
                    <tr class="bg-gray-400 text-black font-medium">
                        <th v-for="column in TableColumns" :key="column.key" :class="[
                            column.align === 'center' ? 'text-center' : ''
                        ]">
                            {{ column.label }}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-if="LeaveDataTable.length === 0">
                        <td colspan="5" class="p-8 text-gray-500">
                            No results found
                        </td>
                    </tr>

                    <tr v-for="row in LeaveDataTable" :key="row.id" class="border-t-4 border-slate-200">

                        <td>{{ row.id }}</td>
                        <td>{{ row.reason }}</td>
                        <td>{{ row.date }}</td>
                        <td class="text-center">
                            <span :class="getStatusColor(row.status)"
                                class="py-2 px-4 rounded-full shadow-sm text-sm font-semibold text-black inline-block">
                                {{ getStatusText(row.status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <Link :href="`/employee/leaves/${row.id}`"
                                class="bg-blue-500 hover:bg-blue-600 text-black text-sm font-semibold py-2 px-4 rounded-lg shadow-md transition-colors">
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