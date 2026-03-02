<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
  stats: Object
});

// Reactive data
const stats = ref({
  presentToday: 0,
  absentToday: 0,
  totalEmployees: 0,
  leavePending: 0,
  leaveApproved: 0,
  leaveRejected: 0
});

// Update stats with props data
onMounted(() => {
  if (props.stats) {
    Object.assign(stats.value, props.stats);
  }
});

</script>

<template>

  <!-- Title Contents -->
  <h1 class="text-3xl font-bold mb-12">Dashboard Overview</h1>

  <!-- Dashboard Cards -->
  <div class="grid grid-cols-3 justify-items-center mb-48">

    <!-- Total Employees Card -->
    <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
      <div class="bg-green-500 rounded-md px-4 py-3">
        <i class="fa fa-user fa-2x" aria-hidden="true" />
      </div>
      <div>
        <p class="text-lg font-medium">Present Today</p>
        <p class="text-center">{{ stats.presentToday }}</p>
      </div>
    </div>

    <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
      <div class="bg-red-500 rounded-md px-4 py-3">
        <i class="fa fa-user fa-2x" aria-hidden="true" />
      </div>
      <div>
        <p class="text-lg font-medium">Absent Today</p>
        <p class="text-center">{{ stats.absentToday }}</p>
      </div>
    </div>

    <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
      <div class="bg-green-500 rounded-md p-3">
        <i class="fa fa-users fa-2x" aria-hidden="true" />
      </div>
      <div>
        <p class="text-lg font-medium">Total Employees</p>
        <p class="text-center">{{ stats.totalEmployees }}</p>
      </div>
    </div>

  </div>

  <!-- Leave Details -->
  <h2 class="text-2xl font-bold mb-12 text-center">Leave Details</h2>

  <div class="grid grid-cols-3 justify-items-center">

    <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
      <div class="bg-yellow-400 rounded-md px-5 py-3">
        <i class="fa fa-hourglass-half fa-2x" aria-hidden="true" />
      </div>
      <div>
        <p class="text-lg font-medium">Leave Pending</p>
        <p class="text-center">{{ stats.leavePending }}</p>
      </div>
    </div>

    <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
      <div class="bg-green-500 rounded-md px-4 py-3">
        <i class="fa fa-check-circle fa-2x" aria-hidden="true" />
      </div>
      <div>
        <p class="text-lg font-medium">Leave Approved</p>
        <p class="text-center">{{ stats.leaveApproved }}</p>
      </div>
    </div>

    <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
      <div class="bg-red-500 rounded-md px-4 py-3">
        <i class="fa fa-times-circle fa-2x" aria-hidden="true" />
      </div>
      <div>
        <p class="text-lg font-medium">Leave Rejected</p>
        <p class="text-center">{{ stats.leaveRejected }}</p>
      </div>
    </div>

  </div>
</template>