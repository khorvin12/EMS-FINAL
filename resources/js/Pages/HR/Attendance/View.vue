<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    employees: {
        type: Array,
        default: () => []
        // Shape: [{ employee_id, employee_name, attendance: [...] }]
    }
});

const searchQuery   = ref('');
const selectedEmp   = ref(null);

const filteredEmployees = computed(() => {
    if (!searchQuery.value) return props.employees;
    const q = searchQuery.value.toLowerCase();
    return props.employees.filter(e =>
        e.employee_id?.toString().toLowerCase().includes(q) ||
        e.employee_name?.toLowerCase().includes(q)
    );
});

const formatTime = (time) => {
    if (!time) return '--';
    const [hours, minutes] = time.toString().split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    return `${hour % 12 || 12}:${minutes} ${ampm}`;
};

const formatDate = (date) => {
    if (!date) return '--';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'numeric', day: 'numeric', year: 'numeric'
    });
};

const getStatusClass = (status) => {
    const classes = {
        'present':  'bg-green-500',
        'late':     'bg-yellow-400',
        'absent':   'bg-red-500',
        'on_leave': 'bg-blue-400',
    };
    return classes[status?.toLowerCase()] || 'bg-gray-400';
};

const getStatusText = (status) => {
    if (!status) return 'N/A';
    return status.replace('_', ' ').split(' ')
        .map(w => w.charAt(0).toUpperCase() + w.slice(1))
        .join(' ');
};

const getHours = (checkIn, checkOut) => {
    if (!checkIn || !checkOut) return 0;
    try {
        const i = new Date(`2000-01-01 ${checkIn}`);
        const o = new Date(`2000-01-01 ${checkOut}`);
        return Math.round((o - i) / (1000 * 60 * 60));
    } catch { return 0; }
};
</script>

<template>

    <!-- ── EMPLOYEE LIST ── -->
    <template v-if="!selectedEmp">

        <h1 class="text-center text-3xl font-bold mb-12">Employee Attendance</h1>

        <div class="flex justify-between mb-6">
            <!-- Search -->
            <div class="bg-white rounded-md p-2">
                <input
                    type="search"
                    v-model="searchQuery"
                    placeholder="Search By Employee ID or Name"
                    class="outline-none px-2 text-sm"
                />
            </div>
            <!-- Back to full list -->
            <Link
                href="/hr/attendance"
                class="bg-white rounded-md px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50"
            >
                ← All Records
            </Link>
        </div>

        <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
            <thead>
                <tr class="bg-gray-400 text-black font-medium">
                    <th class="p-4">SNO</th>
                    <th class="p-6">Employee ID</th>
                    <th class="p-6">Employee Name</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(emp, index) in filteredEmployees"
                    :key="emp.employee_id"
                    class="bg-white text-center border-slate-200 border-t-4"
                >
                    <td class="p-4">{{ index + 1 }}</td>
                    <td class="p-4">{{ emp.employee_id }}</td>
                    <td class="p-4">{{ emp.employee_name }}</td>
                    <td class="p-4">
                        <button
                            @click="selectedEmp = emp"
                            class="bg-blue-500 hover:bg-blue-400 text-white rounded-sm px-4 py-1 text-sm font-medium"
                        >
                            View
                        </button>
                    </td>
                </tr>

                <tr v-if="filteredEmployees.length === 0">
                    <td colspan="4" class="p-8 text-center text-gray-500">No employees found</td>
                </tr>
            </tbody>
        </table>

    </template>

    <!-- ── ATTENDANCE DETAIL ── -->
    <template v-else>

        <h1 class="text-center text-3xl font-bold mb-12">Employee Attendance</h1>

        <div class="flex justify-between mb-6">
            <button
                @click="selectedEmp = null"
                class="bg-white rounded-md px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50"
            >
                ← Back to Employees
            </button>
            <!-- Employee badge -->
            <div class="bg-white rounded-md px-4 py-2 text-sm border border-gray-300 flex items-center gap-2">
                <span class="font-semibold text-gray-700">{{ selectedEmp.employee_name }}</span>
                <span class="text-gray-400">·</span>
                <span class="text-blue-500 font-medium">{{ selectedEmp.employee_id }}</span>
            </div>
        </div>

        <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
            <thead>
                <tr class="bg-gray-400 text-black font-medium">
                    <th class="p-4">SNO</th>
                    <th class="p-6">Employee ID</th>
                    <th class="p-6">Employee Name</th>
                    <th class="p-4">Date</th>
                    <th class="p-4">Check In</th>
                    <th class="p-4">Check Out</th>
                    <th class="p-4">Hours</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(att, index) in selectedEmp.attendance"
                    :key="att.id"
                    class="bg-white text-center border-slate-200 border-t-4"
                >
                    <td class="p-4">{{ index + 1 }}</td>
                    <td class="p-4">{{ att.employee_id }}</td>
                    <td class="p-4">{{ selectedEmp.employee_name }}</td>
                    <td class="p-4">{{ formatDate(att.date) }}</td>
                    <td class="p-4">{{ formatTime(att.check_in) }}</td>
                    <td class="p-4">{{ formatTime(att.check_out) }}</td>
                    <td class="p-4">{{ getHours(att.check_in, att.check_out) }}</td>
                    <td class="p-4">
                        <button
                            :class="getStatusClass(att.status)"
                            class="rounded-sm px-2 py-1 text-white text-sm"
                        >
                            {{ getStatusText(att.status) }}
                        </button>
                    </td>
                    <td class="p-4 space-x-2 inline-flex">
                        <Link
                            :href="`/hr/attendance/${att.id}/edit`"
                            class="bg-yellow-400 hover:bg-yellow-300 rounded-sm px-4 py-1 text-sm"
                        >
                            Edit
                        </Link>
                        <Link
                            :href="`/hr/attendance/${att.id}`"
                            method="delete"
                            as="button"
                            class="bg-red-500 hover:bg-red-400 text-white rounded-sm px-2 py-1 text-sm"
                        >
                            Delete
                        </Link>
                    </td>
                </tr>

                <tr v-if="!selectedEmp.attendance?.length">
                    <td colspan="9" class="p-8 text-center text-gray-500">
                        No attendance records found for this employee
                    </td>
                </tr>
            </tbody>
        </table>

    </template>

</template>