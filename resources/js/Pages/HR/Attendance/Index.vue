<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    attendanceHistory: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');

const filteredAttendances = computed(() => {
    if (!searchQuery.value) {
        return props.attendanceHistory;
    }
    return props.attendanceHistory.filter(attendance => 
        attendance.id?.toString().includes(searchQuery.value) ||
        attendance.employee_name?.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
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
        month: 'numeric',
        day: 'numeric',
        year: 'numeric'
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
        const timeInDate  = new Date(`2000-01-01 ${checkIn}`);
        const timeOutDate = new Date(`2000-01-01 ${checkOut}`);
        const hours = (timeOutDate - timeInDate) / (1000 * 60 * 60) - 1;
        return Math.max(0, Math.round(hours));
    } catch (e) {
        return 0;
    }
};
</script>

<template>
    <h1 class="text-center text-3xl font-bold mb-12">Employee Attendance</h1>

    <div class="flex justify-between mb-6">
        <div class="bg-white rounded-md p-2">
            <input 
                type="search" 
                v-model="searchQuery"
                placeholder="Search By SNO or Name" 
                class="outline-none px-2"
            />
        </div>
    </div>

    <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
        <thead>
            <tr class="bg-gray-400 text-black font-medium">
                <th class="p-4">SNO</th>
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
                v-for="(attendance, index) in filteredAttendances" 
                :key="attendance.id"
                class="bg-white-100 text-center border-slate-200 border-t-4"
            >
                <td class="p-4">{{ index + 1 }}</td>
                <td class="p-4">{{ attendance.employee_name || 'N/A' }}</td>
                <td class="p-4">{{ formatDate(attendance.date) }}</td>
                <td class="p-4">{{ formatTime(attendance.time_in) }}</td>
                <td class="p-4">{{ formatTime(attendance.time_out) }}</td>
                <td class="p-4">{{ getHours(attendance.time_in, attendance.time_out) }}</td>
                <td class="p-4">
                    <button :class="getStatusClass(attendance.status)" class="rounded-sm px-2 py-1 text-white">
                        {{ getStatusText(attendance.status) }}
                    </button>
                </td>
                <td class="p-4 space-x-4 inline-flex">
                    <Link 
                        :href="`/hr/attendance/${attendance.id}/edit`" 
                        class="bg-yellow-400 hover:bg-yellow-300 rounded-sm px-4 py-1"
                    >
                        Edit
                    </Link>
                    <Link
                        :href="`/hr/attendance/${attendance.id}`"
                        method="delete"
                        as="button"
                        class="bg-red-500 hover:bg-red-400 text-white rounded-sm px-2 py-1"
                    >
                        Delete
                    </Link>
                </td>
            </tr>
            
            <tr v-if="filteredAttendances.length === 0">
                <td colspan="8" class="p-8 text-center text-gray-500">
                    No attendance records found
                </td>
            </tr>
        </tbody>
    </table>
</template>