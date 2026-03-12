<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import PaginationLinks from '../../Components/PaginationLinks.vue'

interface Leave {
    id: number
    user: { name: string; id: number }
    reason: string
    start_date: string
    end_date: string
    status: 'pending' | 'approved' | 'rejected'
}

interface PaginatedLeaves {
    data: Leave[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    links: any[]
}

interface Stats {
    pending: number
    approved: number
    rejected: number
}

const props = defineProps<{
    leaves: PaginatedLeaves
    stats: Stats
}>()

const selectedFilter = ref<'all' | 'pending' | 'approved' | 'rejected'>('all')
const searchQuery = ref('')



const filteredLeaves = computed(() => {
    let result = selectedFilter.value === 'all'
        ? props.leaves.data
        : props.leaves.data.filter(l => l.status === selectedFilter.value)

    // Actually use searchQuery
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase()
        result = result.filter(l =>
            l.id.toString().includes(q) ||
            l.user.name.toLowerCase().includes(q)
        )
    }

    return result
})

const filterButtons = computed(() => [
    { label: 'All', value: 'all' as const, count: props.leaves.total, color: 'bg-blue-600' },
    { label: 'Pending', value: 'pending' as const, count: props.stats.pending, color: 'bg-yellow-500' },
    { label: 'Approved', value: 'approved' as const, count: props.stats.approved, color: 'bg-green-500' },
    { label: 'Rejected', value: 'rejected' as const, count: props.stats.rejected, color: 'bg-red-500' }
])

const statusConfig: Record<string, { color: string; label: string }> = {
    pending: { color: 'bg-yellow-400', label: 'Pending' },
    approved: { color: 'bg-green-500', label: 'Approved' },
    rejected: { color: 'bg-red-500', label: 'Rejected' }
}

const getStatusConfig = (status: string) => statusConfig[status] || { color: 'bg-gray-400', label: 'Unknown' }

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric'
    })
}

const leaveTableData = computed(() => {
    const offset = (props.leaves.current_page - 1) * props.leaves.per_page;
    
    return filteredLeaves.value.map((leave, index) => ({
        number: offset + index + 1,
        employee: leave.user.name,
        reason: leave.reason,
        start_date: formatDate(leave.start_date),
        end_date: formatDate(leave.end_date),
        status: getStatusConfig(leave.status),
        id: leave.id
    }))
})

const emptyStateMessage = computed(() =>
    selectedFilter.value === 'all'
        ? 'No leave requests submitted yet'
        : `No ${selectedFilter.value} leave requests`
)
</script>

<template>
    <div class="flex flex-col px-6">
        
        <Head title=" | Leave Management" />

        <h1 class="text-4xl font-bold text-center mb-12">Leave Management</h1>

        <!-- Filter tabs + View by Employee button -->
        <div class="flex justify-between mb-8 flex-wrap gap-6">

            <input v-model="searchQuery" type="text" placeholder="Search by Serial No"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />

            <div class="flex gap-3 flex-wrap justify-end">
                <button v-for="filter in filterButtons" :key="filter.value" @click="selectedFilter = filter.value"
                    :class="[
                        selectedFilter === filter.value
                            ? `${filter.color} text-white`
                            : 'bg-white text-gray-700 hover:bg-gray-100',
                        'px-6 py-2 rounded-lg font-semibold shadow-md transition-colors'
                    ]">
                    {{ filter.label }} ({{ filter.count }})
                </button>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="leaveTableData.length === 0" class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-500 text-lg">{{ emptyStateMessage }}</p>
        </div>

        <!-- Leave Table -->
        <div v-else class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left transition whitespace-nowrap">
                    <thead class="bg-gray-400">
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
                        <tr v-for="row in leaveTableData" :key="row.id" class="border-t-4 border-gray-200">
                            <td>{{ row.number }}</td>
                            <td>{{ row.employee }}</td>
                            <td>{{ row.reason }}</td>
                            <td>{{ row.start_date }}</td>
                            <td>{{ row.end_date }}</td>
                            <td class="px-6 py-4 text-center border-b border-gray-200">
                                <span :class="[
                                    row.status.color,
                                    'inline-block w-24 text-center py-2 rounded-full text-sm font-semibold transition'
                                ]">
                                    {{ row.status.label }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center border-b border-gray-200">
                                <Link :href="`/manageleaves/leaves/review/${row.id}`">
                                    <button
                                        class="w-24 bg-blue-500 hover:bg-blue-600 text-center py-2 rounded-md text-sm font-semibold transition">
                                        Review
                                    </button>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="mt-6">
            <PaginationLinks :paginator="leaves" />
        </div>
    </div>


</template>