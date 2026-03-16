<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    employee:          { type: Object, required: true },
    todayAttendance:   { type: Object, default: null },
    attendanceHistory: { type: Array,  default: () => [] }
});

const toast       = ref(null)
const processing  = ref(false);
const currentTime = ref(new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true }));
const currentDate = ref(new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }));

const canTimeIn  = computed(() => !props.todayAttendance || !props.todayAttendance.check_in);
const canTimeOut = computed(() => props.todayAttendance && props.todayAttendance.check_in && !props.todayAttendance.check_out);

let interval;
onMounted(() => {
    interval = setInterval(() => {
        currentTime.value = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    }, 1000);
});
onUnmounted(() => clearInterval(interval));

const timeIn = () => {
    processing.value = true;
    router.post('/employee/attendance/record', { type: 'time_in' }, {
        preserveScroll: true,
        onSuccess: () => { processing.value = false; },
        onError:   () => { processing.value = false; },
        onFinish:  () => { processing.value = false; }
    });
};

const timeOut = () => {
    processing.value = true;
    router.post('/employee/attendance/record', { type: 'time_out' }, {
        preserveScroll: true,
        onSuccess: () => { processing.value = false; },
        onError:   () => { processing.value = false; },
        onFinish:  () => { processing.value = false; }
    });
};

const formatTime = (timeString) => {
    if (!timeString) return '--';
    const [hours, minutes] = timeString.toString().split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour.toString().padStart(2, '0')}:${minutes} ${ampm}`;
};

const formatDate = (dateString) =>
    new Date(dateString).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

const getStatusColor = (status) => {
    const colors = {
        'present':  'bg-green-500',
        'late':     'bg-yellow-400',
        'absent':   'bg-red-500',
        'on_leave': 'bg-blue-500'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <div class="flex flex-col px-4 md:px-6">

        <Head title=" | Attendance" />

        <div class="mb-6 md:mb-8">
            <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-2">Attendance Tracker</h2>
            <p class="text-sm md:text-base text-gray-600">{{ currentDate }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 md:p-8 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">

                <div>
                    <div class="bg-gray-50 rounded-lg p-4 md:p-8 mb-4 md:mb-6">
                        <p class="text-sm md:text-lg text-gray-600 mb-1">Employee ID</p>
                        <p class="text-lg md:text-2xl font-bold text-gray-900 mb-3 md:mb-4">{{ employee.id }}</p>
                        <p class="text-sm md:text-lg text-gray-600 mb-1">Name</p>
                        <p class="text-lg md:text-2xl font-bold text-gray-900">{{ employee.name }}</p>
                    </div>
                    <div class="text-center bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 md:p-12">
                        <p class="text-sm md:text-lg text-gray-600 mb-2">Current Time</p>
                        <p class="text-4xl md:text-6xl font-bold text-gray-900">{{ currentTime }}</p>
                    </div>
                </div>

                <div class="flex flex-col justify-center">
                    <div v-if="todayAttendance" class="bg-gray-50 rounded-lg p-4 md:p-14 mb-4 md:mb-6">
                        <h3 class="text-lg md:text-2xl font-semibold text-gray-900 mb-4">Today's Attendance</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600 uppercase mb-1">Time In</p>
                                <p class="text-lg md:text-2xl font-bold text-green-600">
                                    {{ todayAttendance.check_in ? formatTime(todayAttendance.check_in) : '--' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase mb-1">Time Out</p>
                                <p class="text-lg md:text-2xl font-bold text-red-600">
                                    {{ todayAttendance.check_out ? formatTime(todayAttendance.check_out) : '--' }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4" v-if="todayAttendance.hours">
                            <p class="text-xs text-gray-600 uppercase mb-1">Hours Worked</p>
                            <p class="text-lg md:text-xl font-bold text-gray-900">{{ todayAttendance.hours }} hours</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <button v-if="canTimeIn" @click="timeIn" :disabled="processing"
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-3 md:py-4 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <span v-if="!processing">Clock In</span>
                            <span v-else>Recording...</span>
                        </button>

                        <button v-if="canTimeOut" @click="timeOut" :disabled="processing"
                            class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white font-bold py-3 md:py-4 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <span v-if="!processing">Clock Out</span>
                            <span v-else>Recording...</span>
                        </button>

                        <div v-if="!canTimeIn && !canTimeOut" class="text-center p-8 md:p-16 bg-gray-50 rounded-lg">
                            <p class="text-gray-700 font-medium">Attendance completed for today!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>