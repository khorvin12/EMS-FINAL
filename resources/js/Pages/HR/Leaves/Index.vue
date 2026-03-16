<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import PaginationLinks from '../../Components/PaginationLinks.vue';

const props = defineProps({
    leaves: Object,
    stats:  Object
})

const searchQuery = ref('')
const activeTab   = ref('all')

const tabCounts = computed(() => ({
    all:      props.stats?.all      ?? 0,
    pending:  props.stats?.pending  ?? 0,
    approved: props.stats?.approved ?? 0,
    rejected: props.stats?.rejected ?? 0,
}))

const filteredLeaves = computed(() => {
    const offset = (props.leaves.current_page - 1) * props.leaves.per_page;
    let result = (props.leaves.data || []).map((l, index) => ({
        ...l,
        serialNo: offset + index + 1
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

    return result;
})

const tabs = computed(() => [
    { key: 'all',      label: 'All',      count: tabCounts.value.all      },
    { key: 'pending',  label: 'Pending',  count: tabCounts.value.pending  },
    { key: 'approved', label: 'Approved', count: tabCounts.value.approved },
    { key: 'rejected', label: 'Rejected', count: tabCounts.value.rejected },
])

const getStatusColor = (status) => {
    switch (status) {
        case 'approved': return 'bg-green-500';
        case 'rejected': return 'bg-red-500';
        case 'pending':  return 'bg-yellow-400';
        default:         return 'bg-gray-500';
    }
}

const getTabStyle = (key) => {
    const base = 'px-6 py-2 rounded-lg font-semibold shadow-md transition-colors '
    const active = {
        all:      'bg-blue-600 text-white',
        pending:  'bg-yellow-500 text-white',
        approved: 'bg-green-500 text-white',
        rejected: 'bg-red-500 text-white',
    }
    return activeTab.value === key
        ? base + active[key]
        : base + 'bg-white text-gray-700 hover:bg-gray-100'
}

const formatDate = (dateString) =>
    new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric'
    })
</script>

<template>
    <div class="flex flex-col px-6">

        <Head title=" | Leave Management" />

        <h1 class="text-center text-4xl font-bold mb-12">Leave Management</h1>

        <div class="flex flex-wrap justify-between mb-8 gap-4">
            <input v-model="searchQuery" type="text" placeholder="Search by Serial No or Name..."
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />

            <div class="flex flex-wrap-reverse justify-end gap-3">
                <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
                    :class="getTabStyle(tab.key)">
                    {{ tab.label }} ({{ tab.count }})
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full text-left whitespace-nowrap">
                <thead class="bg-gray-400 text-black font-medium">
                    <tr>
                        <th class="p-4">Serial No</th>
                        <th class="p-4">Employee</th>
                        <th class="p-4">Reason</th>
                        <th class="p-4">Start Date</th>
                        <th class="p-4">End Date</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="filteredLeaves.length === 0">
                        <td colspan="7" class="text-center p-8 text-gray-500 border-t-4 border-slate-200">
                            No leave requests found
                        </td>
                    </tr>
                    <tr v-for="leave in filteredLeaves" :key="leave.id" class="border-slate-200 border-t-4">
                        <td class="p-4">{{ leave.serialNo }}</td>
                        <td class="p-4">{{ leave.user.name }}</td>
                        <td class="p-4">{{ leave.reason || 'N/A' }}</td>
                        <td class="p-4">{{ formatDate(leave.start_date) }}</td>
                        <td class="p-4">{{ formatDate(leave.end_date) }}</td>
                        <td class="p-4 text-center">
                            <span :class="getStatusColor(leave.status)"
                                class="inline-block w-24 text-center py-2 rounded-full text-sm font-semibold transition">
                                {{ leave.status.charAt(0).toUpperCase() + leave.status.slice(1) }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <Link :href="`/hr/leaves/review/${leave.id}`"
                                class="bg-blue-500 hover:bg-blue-600 text-white inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold transition">
                                Review
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