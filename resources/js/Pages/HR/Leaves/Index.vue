<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    leaves: Array,
    stats: Object
});

const searchQuery = ref('');
const dateFilter  = ref('');
const activeTab   = ref('all'); // 'all' | 'pending' | 'approved' | 'rejected'
const selectedEmp = ref(null);

// Group leaves by employee for "View by Employee" mode
const employeeGroups = computed(() => {
    const map = {};
    (props.leaves || []).forEach(leave => {
        const key = leave.user.id;
        if (!map[key]) {
            map[key] = {
                user_id: leave.user.id,
                name:    leave.user.name,
                email:   leave.user.email,
                leaves:  []
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
    const all      = props.leaves || [];
    return {
        all:      all.length,
        pending:  all.filter(l => l.status === 'pending').length,
        approved: all.filter(l => l.status === 'approved').length,
        rejected: all.filter(l => l.status === 'rejected').length,
    };
});

const filteredLeaves = computed(() => {
    let result = props.leaves || [];

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
    { key: 'all',      label: 'All',      count: tabCounts.value.all },
    { key: 'pending',  label: 'Pending',  count: tabCounts.value.pending },
    { key: 'approved', label: 'Approved', count: tabCounts.value.approved },
    { key: 'rejected', label: 'Rejected', count: tabCounts.value.rejected },
]);

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

const getStatusColor = (status) => {
    switch (status) {
        case 'approved': return 'bg-green-500 text-white';
        case 'rejected': return 'bg-red-500 text-white';
        case 'pending':  return 'bg-yellow-500 text-white';
        default:         return 'bg-gray-500 text-white';
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
    <h1 class="text-center text-3xl font-bold mb-8">Manage Leave Request</h1>

    <!-- ── ALL RECORDS VIEW ── -->
    <template v-if="!selectedEmp">

        <!-- Tab bar + actions row -->
        <div class="flex items-center justify-between mb-6">

            <!-- Status tabs -->
            <div class="flex items-center gap-2">
                <button
                    v-for="tab in tabs"
                    :key="tab.key"
                    @click="activeTab = tab.key"
                    :class="getTabStyle(tab.key)"
                >
                    {{ tab.label }} ({{ tab.count }})
                </button>
            </div>

            <!-- Right-side controls -->
            <div class="flex items-center gap-4">
                <input
                    v-model="searchQuery"
                    type="search"
                    placeholder="Search by Name or ID"
                    class="bg-white rounded-md p-2 border border-gray-300 text-sm"
                />
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-600">Filter by Date</label>
                    <input
                        v-model="dateFilter"
                        type="date"
                        class="bg-white rounded-md p-2 border border-gray-300 text-sm"
                    />
                </div>
                <button
                    @click="searchQuery = ''; selectedEmp = 'list'"
                    class="bg-blue-500 hover:bg-blue-400 text-white rounded-md px-4 py-2 text-sm font-medium"
                >
                    View by Employee
                </button>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
            <thead>
                <tr class="bg-gray-400 text-black font-medium text-sm">
                    <th class="p-4">No.</th>
                    <th class="p-4">Employee</th>
                    <th class="p-4">Reason</th>
                    <th class="p-4">Start Date</th>
                    <th class="p-4">End Date</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="filteredLeaves.length === 0">
                    <td colspan="7" class="text-center p-8 text-gray-500">No leave requests found</td>
                </tr>
                <tr
                    v-for="(leave, index) in filteredLeaves"
                    :key="leave.id"
                    class="bg-white text-center border-slate-200 border-t"
                >
                    <td class="p-4 text-gray-600">{{ String(index + 1).padStart(3, '0') }}</td>
                    <td class="p-4 font-medium">{{ leave.user.name }}</td>
                    <td class="p-4 text-gray-600">{{ leave.reason }}</td>
                    <td class="p-4">{{ formatDate(leave.start_date) }}</td>
                    <td class="p-4">{{ formatDate(leave.end_date) }}</td>
                    <td class="p-4">
                        <span
                            :class="getStatusColor(leave.status)"
                            class="px-3 py-1 rounded-full text-xs font-semibold"
                        >
                            {{ leave.status.charAt(0).toUpperCase() + leave.status.slice(1) }}
                        </span>
                    </td>
                    <td class="p-4">
                        <Link
                            :href="`/hr/leaves/review/${leave.id}`"
                            class="bg-blue-500 hover:bg-blue-400 rounded-md px-4 py-1 text-white text-sm"
                        >
                            Review
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Row count -->
        <p class="text-center text-sm text-gray-500 mt-4">
            Showing {{ filteredLeaves.length }} of {{ (props.leaves || []).length }} leave requests
        </p>
    </template>

    <!-- ── VIEW BY EMPLOYEE LIST ── -->
    <template v-else-if="selectedEmp === 'list'">

        <div class="flex items-center justify-between mb-6">
            <input
                v-model="searchQuery"
                type="search"
                placeholder="Search By Name or ID"
                class="bg-white rounded-md p-2 border border-gray-300 text-sm"
            />
            <button
                @click="searchQuery = ''; selectedEmp = null"
                class="bg-white rounded-md px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50"
            >
                ← All Records
            </button>
        </div>

        <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
            <thead>
                <tr class="bg-gray-400 text-black font-medium text-sm">
                    <th class="p-4">SNO</th>
                    <th class="p-4">Employee ID</th>
                    <th class="p-4">Employee Name</th>
                    <th class="p-4">Total Leaves</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="filteredEmployees.length === 0">
                    <td colspan="5" class="p-8 text-center text-gray-500">No employees found</td>
                </tr>
                <tr
                    v-for="(emp, index) in filteredEmployees"
                    :key="emp.user_id"
                    class="bg-white text-center border-slate-200 border-t"
                >
                    <td class="p-4">{{ index + 1 }}</td>
                    <td class="p-4">{{ emp.user_id }}</td>
                    <td class="p-4 font-medium">{{ emp.name }}</td>
                    <td class="p-4">{{ emp.leaves.length }}</td>
                    <td class="p-4">
                        <button
                            @click="selectedEmp = emp"
                            class="bg-blue-500 hover:bg-blue-400 text-white rounded-md px-4 py-1 text-sm font-medium"
                        >
                            View
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </template>

    <!-- ── INDIVIDUAL EMPLOYEE LEAVE DETAIL ── -->
    <template v-else>

        <div class="flex items-center justify-between mb-6">
            <button
                @click="selectedEmp = 'list'"
                class="bg-white rounded-md px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50"
            >
                ← Back to Employees
            </button>
            <div class="bg-white rounded-md px-4 py-2 text-sm border border-gray-300">
                <span class="font-semibold text-gray-700">{{ selectedEmp.name }}</span>
            </div>
        </div>

        <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
            <thead>
                <tr class="bg-gray-400 text-black font-medium text-sm">
                    <th class="p-4">SNO</th>
                    <th class="p-4">Start Date</th>
                    <th class="p-4">End Date</th>
                    <th class="p-4">Reason</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="!selectedEmp.leaves?.length">
                    <td colspan="6" class="p-8 text-center text-gray-500">No leave records found</td>
                </tr>
                <tr
                    v-for="(leave, index) in selectedEmp.leaves"
                    :key="leave.id"
                    class="bg-white text-center border-slate-200 border-t"
                >
                    <td class="p-4">{{ index + 1 }}</td>
                    <td class="p-4">{{ formatDate(leave.start_date) }}</td>
                    <td class="p-4">{{ formatDate(leave.end_date) }}</td>
                    <td class="p-4">{{ leave.reason }}</td>
                    <td class="p-4">
                        <span
                            :class="getStatusColor(leave.status)"
                            class="px-3 py-1 rounded-full text-xs font-semibold"
                        >
                            {{ leave.status.charAt(0).toUpperCase() + leave.status.slice(1) }}
                        </span>
                    </td>
                    <td class="p-4">
                        <Link
                            :href="`/hr/leaves/review/${leave.id}`"
                            class="bg-blue-500 hover:bg-blue-400 rounded-md px-4 py-1 text-white text-sm"
                        >
                            Review
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </template>
</template>