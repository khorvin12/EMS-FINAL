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

const searchQuery = ref('');
const selectedEmp = ref(null);

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
        'present': 'bg-green-500',
        'late': 'bg-yellow-400',
        'absent': 'bg-red-500',
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
    <h1 class="text-center text-4xl font-bold mb-12">Employee Attendance</h1>

    <!-- ── EMPLOYEE LIST ── -->
    <template v-if="!selectedEmp">

        <div class="flex flex-col px-6">
            <div class="flex justify-between mb-6">
                <!-- Search -->
                <input type="search" v-model="searchQuery" placeholder="Search By Employee ID or Name"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-80 focus:outline-none focus:ring-2 focus:ring-blue-400" />

                <!-- Back to full list -->
                <Link href="/hr/attendance"
                    class="bg-white rounded-md px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50">
                    ← All Records
                </Link>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-gray-400 text-black font-medium">
                        <tr>
                            <th>Serial No</th>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(emp, index) in filteredEmployees" :key="emp.employee_id"
                            class="bg-white border-slate-200 border-t-4">
                            <td>{{ index + 1 }}</td>
                            <td>{{ emp.employee_id }}</td>
                            <td>{{ emp.employee_name }}</td>
                            <td class="text-center">
                                <button @click="selectedEmp = emp"
                                    class="bg-blue-500 hover:bg-blue-600 inline-flex items-center justify-center w-20 py-2 rounded-md text-sm font-semibold transition">
                                    View
                                </button>
                            </td>
                        </tr>

                        <tr v-if="filteredEmployees.length === 0">
                            <td colspan="4" class="p-8 text-center text-gray-500">No employees found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </template>

    <!-- ── ATTENDANCE DETAIL ── -->
    <template v-else>

        <div class="flex flex-col px-6">
            <div class="flex justify-between mb-6">
                <!-- Employee badge -->
                <span
                    class="bg-white px-4 py-2.5 text-sm border border-gray-300 rounded-md font-semibold text-gray-700">{{
                        selectedEmp.employee_name }}</span>

                <!-- Back to employee list -->
                <button @click="selectedEmp = null"
                    class="bg-white rounded-md px-4 py-2.5 text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50">
                    ← Back to Employees
                </button>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-gray-400 text-black font-medium">
                        <tr>
                            <th>Serial No</th>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Hours</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(att, index) in selectedEmp.attendance" :key="att.id"
                            class="bg-white border-slate-200 border-t-4">
                            <td>{{ index + 1 }}</td>
                            <td>{{ att.employee_id }}</td>
                            <td>{{ selectedEmp.employee_name }}</td>
                            <td>{{ formatDate(att.date) }}</td>
                            <td>{{ formatTime(att.check_in) }}</td>
                            <td>{{ formatTime(att.check_out) }}</td>
                            <td>{{ getHours(att.check_in, att.check_out) }}</td>
                            <td class="text-center">
                                <button :class="getStatusClass(att.status)"
                                    class="inline-block w-24 text-center py-2 rounded-full text-sm font-semibold transition">
                                    {{ getStatusText(att.status) }}
                                </button>
                            </td>
                            <td class="flex justify-center gap-3">
                                <Link :href="`/hr/attendance/${att.id}/edit`"
                                    class="bg-yellow-400 hover:bg-yellow-300 inline-flex items-center justify-center w-20 py-2 rounded-md text-sm font-semibold transition">
                                    Edit
                                </Link>
                                <Link :href="`/hr/attendance/${att.id}`" method="delete" as="button"
                                    class="bg-red-500 hover:bg-red-400 inline-flex items-center justify-center w-20 py-2 rounded-md text-sm font-semibold transition">
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
            </div>
        </div>
    </template>

</template>