<script setup>
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();

const props = defineProps({
  attendanceHistory: {
    type: Array,
    default: () => []
  },
  presentDays: Number,
  pendingLeaves: Number,
  estimatedSalary: Number,
});

const getStatusColor = (status) => {
  const colors = {
    'present': 'bg-green-500',
    'late': 'bg-yellow-400',
    'absent': 'bg-red-500',
    'on_leave': 'bg-blue-500'
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
  <div class="flex flex-col px-6">

    <Head title=" | Dashboard" />

    <h1 class="text-4xl font-bold mb-12">Dashboard Overview</h1>

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-16 mb-16">
      <div
        class="bg-white text-center rounded-xl shadow-sm p-8 border-l-4 border-green-500 hover:shadow-md transition-all hover:-translate-y-1 duration-300 overflow-hidden">
        <p class="text-xs md:text-sm text-gray-500 uppercase font-bold tracking-tight">Present Days This Month
        </p>
        <p class="text-lg md:text-3xl font-extrabold text-gray-900 mb-2 leading-none mt-2">
          {{ presentDays ?? 0 }}
        </p>
      </div>

      <div
        class="bg-white text-center rounded-xl shadow-sm p-8 border-l-4 border-yellow-500 hover:shadow-md transition-all hover:-translate-y-1 duration-300 overflow-hidden">
        <p class="text-xs md:text-sm text-gray-500 uppercase font-bold tracking-tight">Pending Requests</p>
        <p class="text-lg md:text-3xl font-extrabold text-gray-900 mb-2 leading-none mt-2">
          {{ pendingLeaves ?? 0 }}
        </p>
      </div>

      <div
        class="bg-white text-center rounded-xl shadow-sm p-8 border-l-4 border-blue-500 hover:shadow-md transition-all hover:-translate-y-1 duration-300 overflow-hidden">
        <p class="text-xs md:text-sm text-gray-500 uppercase font-bold tracking-tight">Estimated Salary</p>
        <p class="text-lg md:text-3xl font-extrabold text-gray-900 mb-2 leading-none mt-2">
          ₱{{ Number(estimatedSalary ?? 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
        </p>
      </div>
    </div>

    <!-- Attendance History -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden whitespace-nowrap">

      <div class="p-6 bg-gray-50 border border-gray-200 rounded-tl-xl rounded-tr-xl">
        <h3 class="text-xl font-bold text-gray-900">Attendance History</h3>
      </div>

      <div class="bg-white shadow-lg overflow-x-auto">
        <table class="min-w-full text-left">
          <thead class="bg-gray-400">
            <tr>
              <th>Date</th>
              <th>Time In</th>
              <th>Time Out</th>
              <th>Hours</th>
              <th class="text-center">Status</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-200">
            <tr v-for="record in attendanceHistory" :key="record.id" class="border-t-4 border-slate-200">
              <td class="font-medium">
                {{ formatDate(record.date) }}
              </td>
              <td>
                {{ formatTime(record.check_in) }}
              </td>
              <td>
                {{ formatTime(record.check_out) }}
              </td>
              <td class="font-semibold">
                {{ record.hours || 0 }} hrs
              </td>
              <td class="text-center">
                <span :class="getStatusColor(record.status)"
                  class="inline-block w-24 text-center py-2 rounded-full text-sm font-semibold transition capitalize">
                  {{ record.status }}
                </span>
              </td>
            </tr>

            <tr v-if="attendanceHistory.length === 0">
              <td colspan="5" class="p-8 text-center text-gray-500 border-t-4 border-slate-200">
                <p>No attendance records found</p>
              </td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
  </div>

</template>