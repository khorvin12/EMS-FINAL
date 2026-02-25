<script setup>
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const props = defineProps({
    attendanceHistory: Array,
    presentDays: Number,
    pendingLeaves: Number,
    estimatedSalary: Number,
});



const getStatusColor = (status) => {
    const colors = {
        'present': 'bg-green-100 text-green-800',
        'late': 'bg-yellow-100 text-yellow-800',
        'absent': 'bg-red-100 text-red-800',
        'on_leave': 'bg-blue-100 text-blue-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

const formatTime = (timeString) => {
    if (!timeString) return '--';
    return new Date('2000-01-01 ' + timeString)
        .toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
};
</script>

<template>
    <h1 class="text-3xl font-bold mb-12">Dashboard Overview</h1>

    <div class="max-w-7xl mx-auto">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-16 mb-16">

            <div
                class="bg-white text-center rounded-xl shadow-sm p-8 border-l-4 border-green-500 hover:shadow-md transition-all hover:-translate-y-1 duration-300">
                <p class="text-md text-gray-500 uppercase font-bold tracking-tight">Present Days This Month
                </p>
                <p class="text-3xl font-extrabold text-gray-900 mb-2 leading-none mt-2">
                    {{ presentDays ?? 0 }}
                </p>
            </div>

            <div
                class="bg-white text-center rounded-xl shadow-sm p-8 border-l-4 border-yellow-500 hover:shadow-md transition-all hover:-translate-y-1 duration-300">
                <p class="text-md text-gray-500 uppercase font-bold tracking-tight">Pending Requests</p>
                <p class="text-3xl font-extrabold text-gray-900 mb-2 leading-none mt-2">
                    {{ pendingLeaves ?? 0 }}
                </p>
            </div>

            <div
                class="bg-white text-center rounded-xl shadow-sm p-8 border-l-4 border-blue-500 hover:shadow-md transition-all hover:-translate-y-1 duration-300">
                <p class="text-md text-gray-500 uppercase font-bold tracking-tight">Estimated Salary</p>
                <p class="text-3xl font-extrabold text-gray-900 mb-2 leading-none mt-2">
                    ₱{{ estimatedSalary?.toLocaleString() ?? '0.00' }}
                </p>
            </div>
        </div>

        <!-- Attendance History -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">

            <div class="p-6 bg-gray-50 border border-gray-200 rounded-tl-xl rounded-tr-xl">
                <h3 class="text-xl font-bold text-gray-900">Attendance History</h3>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="p-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Date</th>
                            <th class="p-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Time In</th>
                            <th class="p-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Time Out</th>
                            <th class="p-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Hours</th>
                            <th class="p-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="record in attendanceHistory" :key="record.id" class="hover:bg-gray-50 transition">
                            <td class="p-6 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ formatDate(record.date) }}
                            </td>
                            <td class="p-6 whitespace-nowrap text-sm text-gray-700">
                                {{ formatTime(record.time_in) }}
                            </td>
                            <td class="p-6 whitespace-nowrap text-sm text-gray-700">
                                {{ formatTime(record.time_out) }}
                            </td>
                            <td class="p-6 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                {{ record.hours || 0 }} hrs
                            </td>
                            <td class="p-6 whitespace-nowrap">
                                <span :class="getStatusColor(record.status)"
                                    class="px-3 py-1 rounded-full text-xs font-semibold uppercase">
                                    {{ record.status }}
                                </span>
                            </td>
                        </tr>

                        <tr v-if="attendanceHistory.length === 0">
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fa fa-calendar-times-o text-4xl mb-3 block"></i>
                                <p>No attendance records found</p>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</template>