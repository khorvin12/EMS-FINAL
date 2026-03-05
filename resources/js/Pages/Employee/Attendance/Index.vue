<script setup>
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    employee: {
        type: Object,
        required: true
    },
    todayAttendance: {
        type: Object,
        default: null
    },
    attendanceHistory: {
        type: Array,
        default: () => []
    }
});

const processing = ref(false);

const currentTime = ref(new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true }));
const currentDate = ref(new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }));

setInterval(() => {
    currentTime.value = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
}, 1000);

// ✅ FIXED: use check_in / check_out not time_in / time_out
const canTimeIn  = computed(() => !props.todayAttendance || !props.todayAttendance.check_in);
const canTimeOut = computed(() => props.todayAttendance && props.todayAttendance.check_in && !props.todayAttendance.check_out);

const timeIn = () => {
    processing.value = true;
    router.post('/employee/attendance/record', { type: 'time_in' }, {
        preserveScroll: true,
        onSuccess: () => { processing.value = false; },
        onError: (errors) => {
            processing.value = false;
            console.error('Error:', errors);
            alert('Failed to clock in. Check console for details.');
        },
        onFinish: () => { processing.value = false; }
    });
};

const timeOut = () => {
    processing.value = true;
    router.post('/employee/attendance/record', { type: 'time_out' }, {
        preserveScroll: true,
        onSuccess: () => { processing.value = false; },
        onError: (errors) => {
            processing.value = false;
            console.error('Error:', errors);
            alert('Failed to clock out. Check console for details.');
        },
        onFinish: () => { processing.value = false; }
    });
};

const formatTime = (timeString) => {
    if (!timeString) return '--';
    return new Date('2000-01-01 ' + timeString).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

const getStatusColor = (status) => {
    const colors = {
        'present':  'bg-green-100 text-green-800',
        'late':     'bg-yellow-100 text-yellow-800',
        'absent':   'bg-red-100 text-red-800',
        'on_leave': 'bg-blue-100 text-blue-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="Attendance" />

    <div class="flex flex-col px-6">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Attendance Tracker</h2>
            <p class="text-gray-600">{{ currentDate }}</p>
        </div>

        <!-- Clock In/Out Card -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Left: Employee Info & Time -->
                <div>
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <p class="text-sm text-gray-600 mb-1">Employee ID</p>
                        <p class="text-xl font-bold text-gray-900 mb-4">{{ employee.id }}</p>
                        <p class="text-sm text-gray-600 mb-1">Name</p>
                        <p class="text-xl font-bold text-gray-900">{{ employee.name }}</p>
                    </div>

                    <div class="text-center bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6">
                        <p class="text-sm text-gray-600 mb-2">Current Time</p>
                        <p class="text-5xl font-bold text-gray-900">{{ currentTime }}</p>
                    </div>
                </div>

                <!-- Right: Actions -->
                <div class="flex flex-col justify-center">
                    <!-- Today's Status -->
                    <div v-if="todayAttendance" class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Attendance</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600 uppercase mb-1">Time In</p>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ todayAttendance.check_in ? formatTime(todayAttendance.check_in) : '--' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase mb-1">Time Out</p>
                                <p class="text-2xl font-bold text-red-600">
                                    {{ todayAttendance.check_out ? formatTime(todayAttendance.check_out) : '--' }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4" v-if="todayAttendance.hours_worked">
                            <p class="text-xs text-gray-600 uppercase mb-1">Hours Worked</p>
                            <p class="text-xl font-bold text-gray-900">{{ todayAttendance.hours_worked }} hours</p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="space-y-4">
                        <button
                            v-if="canTimeIn"
                            @click="timeIn"
                            :disabled="processing"
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-4 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        >
                            <span v-if="!processing"><i class="fa fa-sign-in mr-2"></i>Clock In</span>
                            <span v-else>Recording...</span>
                        </button>

                        <button
                            v-if="canTimeOut"
                            @click="timeOut"
                            :disabled="processing"
                            class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white font-bold py-4 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        >
                            <span v-if="!processing"><i class="fa fa-sign-out mr-2"></i>Clock Out</span>
                            <span v-else>Recording...</span>
                        </button>

                        <div v-if="!canTimeIn && !canTimeOut" class="text-center p-6 bg-gray-50 rounded-lg">
                            <i class="fa fa-check-circle text-green-500 text-4xl mb-2"></i>
                            <p class="text-gray-700 font-medium">Attendance completed for today!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>