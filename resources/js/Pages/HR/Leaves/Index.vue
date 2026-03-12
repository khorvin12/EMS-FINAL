<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PaginationLinks from '../../Components/PaginationLinks.vue';

const props = defineProps({
    leaves: Object,
    stats: Object
});

const searchQuery = ref('');
const dateFilter = ref('');
const activeTab = ref('all'); // 'all' | 'pending' | 'approved' | 'rejected'

// Tab counts derived from raw leaves (always reflect full data)
const tabCounts = computed(() => ({
    all: props.stats?.all ?? 0,
    pending: props.stats?.pending ?? 0,
    approved: props.stats?.approved ?? 0,
    rejected: props.stats?.rejected ?? 0,
}));

const filteredLeaves = computed(() => {
    const offset = (props.leaves.current_page - 1) * props.leaves.per_page;

    let result = (props.leaves.data || []).map((l, index) => ({
        ...l,
        serialNo: offset + index + 1  // fix here
    }));

    if (activeTab.value !== 'all') {
        result = result.filter(l => l.status === activeTab.value);
    }

    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        result = result.filter(l =>
            l.user.name.toLowerCase().includes(q) ||
            l.serialNo.toString() === q
        );
    }

    if (dateFilter.value) {
        result = result.filter(l =>
            l.start_date <= dateFilter.value && l.end_date >= dateFilter.value
        );
    }

    return result;
});

const tabs = computed(() => [
    { key: 'all', label: 'All', count: tabCounts.value.all },
    { key: 'pending', label: 'Pending', count: tabCounts.value.pending },
    { key: 'approved', label: 'Approved', count: tabCounts.value.approved },
    { key: 'rejected', label: 'Rejected', count: tabCounts.value.rejected },
]);

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

const getStatusColor = (status) => {
    switch (status) {
        case 'approved': return 'bg-green-500';
        case 'rejected': return 'bg-red-500';
        case 'pending': return 'bg-yellow-400';
        default: return 'bg-gray-500';
    }
};

const getTabStyle = (key) => {
    const base = 'px-4 py-1.5 rounded-md text-sm font-medium border transition-colors';
    return activeTab.value === key
        ? `${base} bg-blue-500 text-white border-blue-500`
        : `${base} bg-white text-gray-700 border-gray-300 hover:bg-gray-50`;
};
</script>

<template>
    <div class="flex flex-col px-6">

        <Head title=" | Leave Management" />

        <h1 class="text-center text-4xl font-bold mb-12">Leave Management</h1>

        <!-- Tab bar + actions row -->
        <div class="flex flex-wrap justify-between mb-8 gap-4">

            <!-- Search input -->
            <input v-model="searchQuery" type="text" placeholder="Search by Serial No"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />

            <!-- Status tabs -->
            <div class="flex flex-wrap-reverse justify-end gap-3">
                <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key" class="py-2.5 "
                    :class="getTabStyle(tab.key)">
                    {{ tab.label }} ({{ tab.count }})
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full text-left whitespace-nowrap">
                <thead class="bg-gray-400 text-black font-medium">
                    <tr>
                        <th>Serial No</th>
                        <th>Employee</th>
                        <th>Reason</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="filteredLeaves.length === 0">
                        <td colspan="7" class="text-center p-8 text-gray-500 border-t-4 border-slate-200">No leave
                            requests found</td>
                    </tr>
                    <tr v-for="(leave) in filteredLeaves" :key="leave.id" class="border-slate-200 border-t-4">
                        <td>{{ leave.serialNo }}</td>
                        <td>{{ leave.user.name }}</td>
                        <td>{{ leave.reason || 'N/A' }}</td>
                        <td>{{ formatDate(leave.start_date) }}</td>
                        <td>{{ formatDate(leave.end_date) }}</td>
                        <td class="text-center">
                            <span :class="getStatusColor(leave.status)"
                                class="inline-block w-24 text-center py-2 rounded-full text-sm font-semibold transition">
                                {{ leave.status.charAt(0).toUpperCase() + leave.status.slice(1) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <Link :href="`/hr/leaves/review/${leave.id}`"
                                class="bg-blue-500 hover:bg-blue-600 inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold transition">
                                Review
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Row count -->
        <div class="mt-6">
            <PaginationLinks :paginator="leaves" />
        </div>
    </div>
</template>