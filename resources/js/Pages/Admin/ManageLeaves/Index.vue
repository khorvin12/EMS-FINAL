<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import PaginationLinks from '../../Components/PaginationLinks.vue'

interface Leave {
    id: number
    user: { name: string; id: number }
    reason: string
    description?: string
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
const selectedEmp = ref<null | 'list' | any>(null)
const searchQuery = ref('')

// Group leaves by employee for View by Employee mode
const employeeGroups = computed(() => {
    const map: Record<number, any> = {}
    props.leaves.data.forEach(leave => {
        const key = leave.user.id
        if (!map[key]) {
            map[key] = { user_id: leave.user.id, name: leave.user.name, leaves: [] }
        }
        map[key].leaves.push(leave)
    })
    return Object.values(map)
})

const filteredEmployees = computed(() => {
    if (!searchQuery.value) return employeeGroups.value
    const q = searchQuery.value.toLowerCase()
    return employeeGroups.value.filter((e: any) =>
        e.name?.toLowerCase().includes(q) ||
        e.user_id?.toString().includes(q)
    )
})

const filteredLeaves = computed(() => {
    let result = selectedFilter.value === 'all'
        ? props.leaves.data
        : props.leaves.data.filter(l => l.status === selectedFilter.value)
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
    approved: { color: 'bg-green-400', label: 'Approved' },
    rejected: { color: 'bg-red-400', label: 'Rejected' }
}

const getStatusConfig = (status: string) => statusConfig[status] || { color: 'bg-gray-400', label: 'Unknown' }

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric'
    })
}

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

const emptyStateMessage = computed(() =>
    selectedFilter.value === 'all'
        ? 'No leave requests submitted yet'
        : `No ${selectedFilter.value} leave requests`
)
</script>

<template>
    <div>
        <h1 class="text-4xl font-bold text-center mb-12">Manage Leaves</h1>

        <!-- ── ALL RECORDS VIEW ── -->
        <template v-if="!selectedEmp">
            <div class="flex flex-col px-6">

                <!-- Filter tabs + View by Employee button -->
                <div class="flex items-center justify-between mb-8 flex-wrap gap-4">

                    <div class="flex gap-4 flex-wrap">
                        <button v-for="filter in filterButtons" :key="filter.value"
                            @click="selectedFilter = filter.value" :class="[
                                selectedFilter === filter.value
                                    ? `${filter.color} text-white`
                                    : 'bg-white text-gray-700 hover:bg-gray-100',
                                'px-6 py-2 rounded-lg font-semibold shadow-md transition-colors'
                            ]">
                            {{ filter.label }} ({{ filter.count }})
                        </button>
                    </div>

                    <button @click="selectedEmp = 'list'"
                        class="bg-blue-500 hover:bg-blue-600 rounded-md px-4 py-2 font-semibold">
                        View by Employee
                    </button>
                </div>

                <!-- Empty State -->
                <div v-if="leaveTableData.length === 0" class="bg-white rounded-lg shadow-md p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-500 text-lg">{{ emptyStateMessage }}</p>
                </div>

                <!-- Leave Table -->
                <div v-else class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left">
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
                                            'inline-block w-20 text-center py-2 rounded-full text-sm font-semibold transition'
                                        ]">
                                            {{ row.status.label }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center border-b border-gray-200">
                                        <Link :href="`/manageleaves/leaves/review/${row.id}`">
                                            <button
                                                class="w-20 bg-blue-500 hover:bg-blue-600 text-center py-2 rounded-md text-sm font-semibold transition">
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

        <!-- ── VIEW BY EMPLOYEE LIST ── -->
        <template v-else-if="selectedEmp === 'list'">

            <div class="flex flex-col px-6">
                <div class="flex items-center justify-between mb-6">
                    <input v-model="searchQuery" type="search" placeholder="Search By Name or ID"
                        class="border border-gray-300 rounded-lg px-4 py-2 w-80 focus:outline-none focus:ring-2 focus:ring-blue-400" />

                    <button @click="searchQuery = ''; selectedEmp = null"
                        class="bg-white rounded-md px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50">
                        ← All Records
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead class="bg-gray-400">
                            <tr>
                                <th>Serial No</th>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th class="text-center">Total Leaves</th>
                                <th class="text-center">Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredEmployees.length === 0">
                                <td colspan="5" class="p-8 text-center text-gray-500">No employees found</td>
                            </tr>

                            <tr v-for="(emp, index) in filteredEmployees" :key="emp.user_id"
                                class="border-t-4 border-gray-200">
                                <td>{{ Number(index) + 1 }}</td>
                                <td>{{ emp.user_id }}</td>
                                <td>{{ emp.name }}</td>
                                <td class="text-center">{{ emp.leaves.length }}</td>
                                <td class="text-center">
                                    <button @click="selectedEmp = emp"
                                        class="w-20 bg-blue-500 hover:bg-blue-600 text-center py-2 rounded-md text-sm font-semibold transition">
                                        View
                                    </button>
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

        <!-- ── INDIVIDUAL EMPLOYEE LEAVE DETAIL ── -->
        <template v-else>
            <div class="flex flex-col px-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-white rounded-md px-4 py-2 text-sm border border-gray-300 flex items-center gap-2">
                        <span class="font-semibold text-gray-700">{{ selectedEmp.name }}</span>
                    </div>

                    <button @click="selectedEmp = 'list'"
                        class="bg-white rounded-md px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50">
                        ← Back to Employees
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead class="bg-gray-400">
                            <tr>
                                <th>Serial No</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Reason</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!selectedEmp.leaves?.length">
                                <td colspan="6" class="p-8 text-center text-gray-500">No leave records found</td>
                            </tr>

                            <tr v-for="(leave, index) in selectedEmp.leaves" :key="leave.id"
                                class="border-t-4 border-gray-200">
                                <td>{{ Number(index) + 1 }}</td>
                                <td>{{ formatDate(leave.start_date) }}</td>
                                <td>{{ formatDate(leave.end_date) }}</td>
                                <td>{{ leave.reason }}</td>
                                <td class="text-center">
                                    <span
                                        :class="[getStatusConfig(leave.status).color, 'inline-block w-24 text-center py-2 rounded-full text-sm font-semibold transition']">
                                        {{ getStatusConfig(leave.status).label }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <Link :href="`/manageleaves/leaves/review/${leave.id}`">
                                        <button
                                            class="w-20 bg-blue-500 hover:bg-blue-600 text-center py-2 rounded-md text-sm font-semibold transition">
                                            Review
                                        </button>
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

    </div>
</template>