<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

interface Leave {
    id: number
    user: { name: string }
    reason: string
    description?: string
    start_date: string
    end_date: string
    status: 'pending' | 'approved' | 'rejected'
}

interface Stats {
    pending: number
    approved: number
    rejected: number
}

const props = defineProps<{
    leaves: Leave[]
    stats: Stats
}>()


const selectedFilter = ref<'all' | 'pending' | 'approved' | 'rejected'>('all')

const filteredLeaves = computed(() => {
    if (selectedFilter.value === 'all') return props.leaves
    return props.leaves.filter(leave => leave.status === selectedFilter.value)
})
 

const filterButtons = computed(() => [
    { label: 'All', value: 'all' as const, count: props.leaves.length, color: 'bg-blue-600' },
    { label: 'Pending', value: 'pending' as const, count: props.stats.pending, color: 'bg-yellow-500' },
    { label: 'Approved', value: 'approved' as const, count: props.stats.approved, color: 'bg-green-500' },
    { label: 'Rejected', value: 'rejected' as const, count: props.stats.rejected, color: 'bg-red-500' }
])


const statusConfig: Record<string, { color: string; label: string }> = {
    pending: { color: 'bg-yellow-400', label: 'Pending' },
    approved: { color: 'bg-green-400', label: 'Approved' },
    rejected: { color: 'bg-red-400', label: 'Rejected' }
}

const getStatusConfig = (status: string) => {
    return statusConfig[status] || { color: 'bg-gray-400', label: 'Unknown' }
}

const formatDate = (dateString: string) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    })
}

const tableColumns = [
    { label: 'No.', key: 'number', align: 'left' },
    { label: 'Employee', key: 'employee', align: 'left' },
    { label: 'Reason', key: 'reason', align: 'left' },
    { label: 'Start Date', key: 'start_date', align: 'left' },
    { label: 'End Date', key: 'end_date', align: 'left' },
    { label: 'Status', key: 'status', align: 'center' },
    { label: 'Actions', key: 'actions', align: 'center' }
]

const leaveTableData = computed(() => 
    filteredLeaves.value.map((leave, index) => ({
        number: String(index + 1).padStart(3, '0'),
        employee: leave.user.name,
        reason: leave.reason,
        start_date: formatDate(leave.start_date),
        end_date: formatDate(leave.end_date),
        status: getStatusConfig(leave.status),
        id: leave.id
    }))
)


const emptyStateMessage = computed(() => {
    return selectedFilter.value === 'all' 
        ? 'No leave requests submitted yet' 
        : `No ${selectedFilter.value} leave requests`
})
</script>

<template>
    <main class="p-6 min-h-screen bg-gray-50">
        
        <!-- Header -->
        <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">
            Manage Leaves
        </h1>

 
        <div class="flex justify-center gap-4 mb-8 flex-wrap">
            <button
                v-for="filter in filterButtons"
                :key="filter.value"
                @click="selectedFilter = filter.value"
                :class="[
                    selectedFilter === filter.value 
                        ? `${filter.color} text-white` 
                        : 'bg-white text-gray-700 hover:bg-gray-100',
                    'px-6 py-2 rounded-lg font-semibold shadow-md transition-colors'
                ]"
            >
                {{ filter.label }} ({{ filter.count }})
            </button>
        </div>

        <!-- Empty State -->
        <div v-if="leaveTableData.length === 0" class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-500 text-lg">{{ emptyStateMessage }}</p>
        </div>

        <!-- Leave Table -->
        <div v-else class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="text-base min-w-full text-left">
                    <!-- Table Header -->
                    <thead class="bg-gray-100">
                        <tr>
                            <th 
                                v-for="column in tableColumns" 
                                :key="column.key"
                                :class="[
                                    'px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold',
                                    column.align === 'center' ? 'text-center' : ''
                                ]"
                            >
                                {{ column.label }}
                            </th>
                        </tr>
                    </thead>


                    <tbody>
                        <tr 
                            v-for="row in leaveTableData" 
                            :key="row.id" 
                            class="hover:bg-gray-50 transition-colors"
                        >
                            <td class="px-6 py-4 border-b border-gray-200">
                                {{ row.number }}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200">
                                {{ row.employee }}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200">
                                {{ row.reason }}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200">
                                {{ row.start_date }}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200">
                                {{ row.end_date }}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200">
                                <div class="flex justify-center">
                                    <span 
                                        :class="[
                                            row.status.color,
                                            'py-2 px-4 rounded-md text-sm shadow-sm font-bold text-white inline-block'
                                        ]"
                                    >
                                        {{ row.status.label }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center border-b border-gray-200">
                                <Link :href="`/manageleaves/leaves/review/${row.id}`">
                                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-colors">
                                        Review
                                    </button>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="leaveTableData.length > 0" class="mt-4 text-center text-gray-600">
            Showing {{ leaveTableData.length }} of {{ leaves.length }} leave requests
        </div>
    </main>
</template>