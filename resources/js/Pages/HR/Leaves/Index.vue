<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PaginationLinks from '../../Components/PaginationLinks.vue';

const props = defineProps({
    leaves: Object,
    stats: Object
});

const searchQuery = ref('');
const dateFilter = ref('');
const activeTab = ref('all'); // 'all' | 'pending' | 'approved' | 'rejected'
const selectedEmp = ref(null);

// Group leaves by employee for "View by Employee" mode
const employeeGroups = computed(() => {
    const map = {};
    (props.leaves.data || []).forEach(leave => {
        const key = leave.user.id;
        if (!map[key]) {
            map[key] = {
                user_id: leave.user.id,
                name: leave.user.name,
                email: leave.user.email,
                leaves: []
            };
        }
        map[key].leaves.push(leave);
    });
    return Object.values(map);
});

const filteredEmployees = computed(() => {
    if (!searchQuery.value) return employeeGroups.value;
    const q = searchQuery.value.toLowerCase();
    return employeeGroups.value.filter(e =>
        e.name?.toLowerCase().includes(q) ||
        e.user_id?.toString().includes(q)
    );
});

// Tab counts derived from raw leaves (always reflect full data)
const tabCounts = computed(() => {
    const all = props.leaves.data || [];
    return {
        all: all.length,
        pending: all.filter(l => l.status === 'pending').length,
        approved: all.filter(l => l.status === 'approved').length,
        rejected: all.filter(l => l.status === 'rejected').length,
    };
});

const filteredLeaves = computed(() => {
    let result = props.leaves.data || [];

    // Tab filter
    if (activeTab.value !== 'all') {
        result = result.filter(l => l.status === activeTab.value);
    }

    // Search filter
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        result = result.filter(l =>
            l.user.name.toLowerCase().includes(q) ||
            l.id.toString().includes(q)
        );
    }

    // Date filter
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

    <h1 class="text-center text-4xl font-bold mb-12">Manage Leave Request</h1>

    <!-- ── ALL RECORDS VIEW ── -->
    <template v-if="!selectedEmp">
        <div class="flex flex-col px-6">
            <!-- Tab bar + actions row -->
            <div class="flex items-center justify-between mb-6">

                <!-- Status tabs -->
                <div class="flex items-center gap-2">
                    <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key" class="py-2.5 "
                        :class="getTabStyle(tab.key)">
                        {{ tab.label }} ({{ tab.count }})
                    </button>
                </div>

                <!-- Right-side controls -->

                <button @click="searchQuery = ''; selectedEmp = 'list'"
                    class="bg-blue-500 hover:bg-blue-600 rounded-lg px-4 py-3 text-sm font-semibold">
                    View by Employee
                </button>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
                <table class="min-w-full text-left">
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
                        <tr v-for="(leave, index) in filteredLeaves" :key="leave.id"
                            class="border-slate-200 border-t-4">
                            <td>{{ String(index + 1).padStart(3, '0') }}</td>
                            <td>{{ leave.user.name }}</td>
                            <td>{{ leave.reason }}</td>
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
                    <thead class="bg-gray-400 text-black font-medium">
                        <tr>
                            <th>Serial No</th>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Total Leaves</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="filteredEmployees.length === 0">
                            <td colspan="5" class="p-8 text-center text-gray-500 border-t-4 border-slate-200">No
                                employees
                                found</td>
                        </tr>
                        <tr v-for="(emp, index) in filteredEmployees" :key="emp.user_id"
                            class="border-slate-200 border-t-4">
                            <td>{{ index + 1 }}</td>
                            <td>{{ emp.user_id }}</td>
                            <td>{{ emp.name }}</td>
                            <td>{{ emp.leaves.length }}</td>
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
        </div>
    </template>

    <!-- ── INDIVIDUAL EMPLOYEE LEAVE DETAIL ── -->
    <template v-else>

        <div class="flex flex-col px-6">
            <div class="flex items-center justify-between mb-6">
                <div class="bg-white rounded-md px-4 py-2.5 text-sm border border-gray-300">
                    <span class="font-semibold text-gray-700">{{ selectedEmp.name }}</span>
                </div>

                <button @click="selectedEmp = 'list'"
                    class="bg-white rounded-md px-4 py-2.5 text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50">
                    ← Back to Employees
                </button>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-gray-400 text-black font-medium">
                        <tr>
                            <th>Serial No</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Reason</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!selectedEmp.leaves?.length">
                            <td colspan="6" class="p-8 text-center text-gray-500 border-t-4 border-slate-200">No leave
                                records found</td>
                        </tr>
                        <tr v-for="(leave, index) in selectedEmp.leaves" :key="leave.id"
                            class="border-slate-200 border-t-4">
                            <td>{{ index + 1 }}</td>
                            <td>{{ formatDate(leave.start_date) }}</td>
                            <td>{{ formatDate(leave.end_date) }}</td>
                            <td>{{ leave.reason }}</td>
                            <td class="text-center">
                                <span :class="getStatusColor(leave.status)"
                                    class="inline-block w-24 text-center py-2 rounded-full text-sm font-semibold transition">
                                    {{ leave.status.charAt(0).toUpperCase() + leave.status.slice(1) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <Link :href="`/hr/leaves/review/${leave.id}`"
                                    class="w-20 bg-blue-500 hover:bg-blue-600 text-center py-2 rounded-md text-sm font-semibold transition inline-block">
                                    Review
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </template>

</template>