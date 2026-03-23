<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import PaginationLinks from '../../Components/PaginationLinks.vue';

const props = defineProps({
    attendanceHistory: {
        type: Object,
        default: () => ({ data: [], current_page: 1, last_page: 1, total: 0 })
    },
    filters: Object
});

const searchQuery = ref(props.filters?.search ?? '');
const dateFrom = ref(props.filters?.date_from ?? '');
const dateTo = ref(props.filters?.date_to ?? '');

let searchTimeout = null;

const triggerFetch = (isSearch = false) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            '/hr/attendance',
            {
                search: searchQuery.value || undefined,
                date_from: dateFrom.value || undefined,
                date_to: dateTo.value || undefined,
                page: 1,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                only: ['attendanceHistory', 'filters'],
            }
        );
    }, isSearch ? 500 : 0);
};

const clearFilters = () => {
    searchQuery.value = '';
    dateFrom.value = '';
    dateTo.value = '';
    triggerFetch();
};

watch(searchQuery, () => triggerFetch(true));
watch(dateFrom, () => triggerFetch(false));
watch(dateTo, () => triggerFetch(false));

const filteredAttendances = computed(() => {
    return props.attendanceHistory.data.map(attendance => ({
        ...attendance,
        serialNo: attendance.serial_no, // ← from server, always correct
    }));
});

const pdfUrl = computed(() => {
    const params = new URLSearchParams();
    if (dateFrom.value) params.append('date_from', dateFrom.value);
    if (dateTo.value) params.append('date_to', dateTo.value);
    return `/hr/reports/attendance?${params.toString()}`;
});

const formatTime = (time) => {
    if (!time) return '--';
    const [hours, minutes] = time.toString().split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
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
        'on_leave': 'bg-blue-400'
    };
    return classes[status?.toLowerCase()] || 'bg-gray-400';
};

const getStatusText = (status) => {
    if (!status) return 'N/A';
    return status.replace('_', ' ').split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};

const getHours = (checkIn, checkOut) => {
    if (!checkIn || !checkOut) return 0;
    try {
        const timeIn = new Date(`2000-01-01 ${checkIn}`);
        const timeOut = new Date(`2000-01-01 ${checkOut}`);
        const hours = (timeOut - timeIn) / (1000 * 60 * 60) - 1;
        return Math.max(0, Math.round(hours));
    } catch (e) { return 0; }
};

const actionButtons = [
    { label: 'Edit', href: (id) => `/hr/attendance/${id}/edit`, color: 'bg-yellow-400 hover:bg-yellow-500' },
    { label: 'Delete', href: (id) => `/hr/attendance/${id}`, color: 'bg-red-500 hover:bg-red-600', method: 'delete', as: 'button' }
];

const Tablecolumns = [
    { label: 'Serial No', key: 'serial_no' },
    { label: 'Employee ID', key: 'employee_id' },
    { label: 'Name', key: 'employee_name' },
    { label: 'Date', key: 'date' },
    { label: 'Check In', key: 'check_in' },
    { label: 'Check Out', key: 'check_out' },
    { label: 'Hours', key: 'hours' },
    { label: 'Status', key: 'status', align: 'center' },
    { label: 'Action', key: 'actions', align: 'center' }
];
</script>

<template>
    <div class="flex flex-col px-4 md:px-6">

        <Head title=" | Attendance Management" />

        <h1 class="text-center text-2xl md:text-4xl font-bold mb-6 md:mb-12">Attendance Management</h1>

        <!-- Filters Section -->
        <div class="flex flex-col md:flex-row md:flex-wrap justify-between md:items-center gap-3 md:gap-4 mb-8">

            <!-- Search Input -->
            <input type="search" v-model="searchQuery" placeholder="Search by Serial No"
                class="border border-gray-300 rounded-lg px-4 py-2 w-56 focus:outline-none focus:ring-2 focus:ring-blue-400" />

            <!-- Date Filters -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600 whitespace-nowrap">From</label>
                    <input type="date" v-model="dateFrom"
                        class="border border-gray-300 rounded-lg w-36 p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600 whitespace-nowrap">To</label>
                    <input type="date" v-model="dateTo"
                        class="border border-gray-300 rounded-lg w-36 p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />

                    <!-- Clear Button next to To Date -->
                    <button @click="clearFilters"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                        Clear
                    </button>
                </div>
            </div>

            <!-- Generate Report Button -->
            <div class="flex justify-end">
                <a :href="pdfUrl" target="_blank"
                    class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 w-48 rounded-lg text-center">
                    Attendance Report
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full text-left transition whitespace-nowrap">
                <thead class="bg-gray-400">
                    <tr>
                        <th v-for="column in Tablecolumns" :key="column.key"
                            :class="[column.align === 'center' ? 'text-center' : '']">{{ column.label }}</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(attendance, index) in filteredAttendances" :key="attendance.id"
                        class="border-t-4 border-gray-200">
                        <td>{{ attendance.serialNo }}</td>
                        <td>{{ 'EMP-' + String(attendance.employee_id).padStart(3, '0') }}</td>
                        <td>{{ attendance.employee_name || 'N/A' }}</td>
                        <td>{{ formatDate(attendance.date) }}</td>
                        <td>{{ formatTime(attendance.check_in) }}</td>
                        <td>{{ formatTime(attendance.check_out) }}</td>
                        <td>{{ getHours(attendance.check_in, attendance.check_out) }}</td>
                        <td class="text-center">
                            <span :class="getStatusClass(attendance.status)"
                                class="inline-block w-24 text-center py-2 rounded-full text-sm font-semibold transition">
                                {{ getStatusText(attendance.status) }}
                            </span>
                        </td>
                        <td class="flex justify-center gap-3">
                            <Link v-for="action in actionButtons" :key="action.label" :href="action.href(attendance.id)"
                                :method="action.method" :as="action.as"
                                :class="[action.color, 'inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold transition']">
                                {{ action.label }}
                            </Link>
                        </td>
                    </tr>

                    <tr v-if="filteredAttendances.length === 0" class="border-t-4 border-gray-200">
                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                            No attendance records found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <PaginationLinks :paginator="attendanceHistory" />
        </div>
    </div>
</template>