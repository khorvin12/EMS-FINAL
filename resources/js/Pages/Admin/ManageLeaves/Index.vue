<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

interface Leave {
    id: number
    user: { name: string; id: number }
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
const selectedEmp    = ref<null | 'list' | any>(null)
const searchQuery    = ref('')

// Group leaves by employee for View by Employee mode
const employeeGroups = computed(() => {
    const map: Record<number, any> = {}
    props.leaves.forEach(leave => {
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
        ? props.leaves
        : props.leaves.filter(l => l.status === selectedFilter.value)
    return result
})

const filterButtons = computed(() => [
    { label: 'All',      value: 'all'      as const, count: props.leaves.length,  color: 'bg-blue-600' },
    { label: 'Pending',  value: 'pending'  as const, count: props.stats.pending,  color: 'bg-yellow-500' },
    { label: 'Approved', value: 'approved' as const, count: props.stats.approved, color: 'bg-green-500' },
    { label: 'Rejected', value: 'rejected' as const, count: props.stats.rejected, color: 'bg-red-500' }
])

const statusConfig: Record<string, { color: string; label: string }> = {
    pending:  { color: 'bg-yellow-400', label: 'Pending' },
    approved: { color: 'bg-green-400',  label: 'Approved' },
    rejected: { color: 'bg-red-400',    label: 'Rejected' }
}

const getStatusConfig = (status: string) => statusConfig[status] || { color: 'bg-gray-400', label: 'Unknown' }

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric'
    })
}

const leaveTableData = computed(() =>
    filteredLeaves.value.map((leave, index) => ({
        number:     String(index + 1).padStart(3, '0'),
        employee:   leave.user.name,
        reason:     leave.reason,
        start_date: formatDate(leave.start_date),
        end_date:   formatDate(leave.end_date),
        status:     getStatusConfig(leave.status),
        id:         leave.id
    }))
)

const emptyStateMessage = computed(() =>
    selectedFilter.value === 'all'
        ? 'No leave requests submitted yet'
        : `No ${selectedFilter.value} leave requests`
)
</script>

<template>
    <main class="p-6 min-h-screen bg-gray-50">
        <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Manage Leaves</h1>

        <!-- ── ALL RECORDS VIEW ── -->
        <template v-if="!selectedEmp">

            <!-- Filter tabs + View by Employee button -->
            <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
                <div class="flex gap-4 flex-wrap">
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
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold">No.</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold">Employee</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold">Reason</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold">Start Date</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold">End Date</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold text-center">Status</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in leaveTableData" :key="row.id" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 border-b border-gray-200">{{ row.number }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ row.employee }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ row.reason }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ row.start_date }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ row.end_date }}</td>
                                <td class="px-6 py-4 border-b border-gray-200 text-center">
                                    <span :class="[row.status.color, 'py-2 px-4 rounded-md text-sm shadow-sm font-bold text-white inline-block']">
                                        {{ row.status.label }}
                                    </span>
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
        </template>

        <!-- ── VIEW BY EMPLOYEE LIST ── -->
        <template v-else-if="selectedEmp === 'list'">
            <div class="flex items-center justify-between mb-6">
                <input v-model="searchQuery" type="search" placeholder="Search By Name or ID"
                    class="bg-white rounded-md p-2 border border-gray-300" />
                <button @click="searchQuery = ''; selectedEmp = null"
                    class="bg-white rounded-md px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50">
                    ← All Records
                </button>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="text-base min-w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold">SNO</th>
                            <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold">Employee ID</th>
                            <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold">Employee Name</th>
                            <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold text-center">Total Leaves</th>
                            <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="filteredEmployees.length === 0">
                            <td colspan="5" class="p-8 text-center text-gray-500">No employees found</td>
                        </tr>
                        <tr v-for="(emp, index) in filteredEmployees" :key="emp.user_id"
                            class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 border-b border-gray-200">{{ index + 1 }}</td>
                            <td class="px-6 py-4 border-b border-gray-200">{{ emp.user_id }}</td>
                            <td class="px-6 py-4 border-b border-gray-200">{{ emp.name }}</td>
                            <td class="px-6 py-4 border-b border-gray-200 text-center">{{ emp.leaves.length }}</td>
                            <td class="px-6 py-4 border-b border-gray-200 text-center">
                                <button @click="selectedEmp = emp"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-colors">
                                    View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>

        <!-- ── INDIVIDUAL EMPLOYEE LEAVE DETAIL ── -->
        <template v-else>
            <div class="flex items-center justify-between mb-6">
                <button @click="selectedEmp = 'list'"
                    class="bg-white rounded-md px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50">
                    ← Back to Employees
                </button>
                <div class="bg-white rounded-md px-4 py-2 text-sm border border-gray-300 flex items-center gap-2">
                    <span class="font-semibold text-gray-700">{{ selectedEmp.name }}</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="text-base min-w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold">SNO</th>
                            <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold">Start Date</th>
                            <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold">End Date</th>
                            <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold">Reason</th>
                            <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold text-center">Status</th>
                            <th class="px-6 py-4 border-b-2 border-gray-200 text-gray-700 font-bold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!selectedEmp.leaves?.length">
                            <td colspan="6" class="p-8 text-center text-gray-500">No leave records found</td>
                        </tr>
                        <tr v-for="(leave, index) in selectedEmp.leaves" :key="leave.id"
                            class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 border-b border-gray-200">{{ index + 1 }}</td>
                            <td class="px-6 py-4 border-b border-gray-200">{{ formatDate(leave.start_date) }}</td>
                            <td class="px-6 py-4 border-b border-gray-200">{{ formatDate(leave.end_date) }}</td>
                            <td class="px-6 py-4 border-b border-gray-200">{{ leave.reason }}</td>
                            <td class="px-6 py-4 border-b border-gray-200 text-center">
                                <span :class="[getStatusConfig(leave.status).color, 'py-2 px-4 rounded-md text-sm shadow-sm font-bold text-white inline-block']">
                                    {{ getStatusConfig(leave.status).label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200 text-center">
                                <Link :href="`/manageleaves/leaves/review/${leave.id}`">
                                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-colors">
                                        Review
                                    </button>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>

    </main>
</template>