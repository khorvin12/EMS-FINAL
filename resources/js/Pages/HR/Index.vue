<script setup>
const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      presentToday: 0,
      absentToday: 0,
      totalEmployees: 0,
      leavePending: 0,
      leaveApproved: 0,
      leaveRejected: 0
    })
  }
});
</script>

<template>

  <div class="mb-12">
    <h1 class="text-4xl font-bold">Dashboard Overview</h1>
    <p class="text-sm text-gray-500 mt-1">Today's attendance and leave summary</p>
  </div>

  <h2 class="text-2xl text-center font-semibold uppercase tracking-widest mb-6">Attendance Today</h2>

  <div class="grid grid-cols-1 sm:grid-cols-3 justify-items-center mb-48 gap-4">

    <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
      <div class="bg-green-500 rounded-md px-4 py-3">
        <i class="fa fa-user fa-2x" aria-hidden="true" />
      </div>
      <div>
        <p class="text-lg font-medium">Present Today</p>
        <p class="text-center">{{ props.stats.presentToday }}</p>
      </div>
    </div>

    <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
      <div class="bg-red-500 rounded-md px-4 py-3">
        <i class="fa fa-user fa-2x" aria-hidden="true" />
      </div>
      <div>
        <p class="text-lg font-medium">Absent Today</p>
        <p class="text-center">{{ props.stats.absentToday }}</p>
      </div>
    </div>

    <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
      <div class="bg-green-500 rounded-md p-3">
        <i class="fa fa-users fa-2x" aria-hidden="true" />
      </div>
      <div>
        <p class="text-lg font-medium">Total Employees</p>
        <p class="text-center">{{ props.stats.totalEmployees }}</p>
      </div>
    </div>

  </div>

  <h2 class="text-2xl text-center font-semibold uppercase tracking-widest mb-6">Leave Requests</h2>

  <div class="grid grid-cols-1 sm:grid-cols-3 justify-items-center gap-4">

    <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
      <div class="bg-yellow-400 rounded-md px-5 py-3">
        <i class="fa fa-hourglass-half fa-2x" aria-hidden="true" />
      </div>
      <div>
        <p class="text-lg font-medium">Leave Pending</p>
        <p class="text-center">{{ props.stats.leavePending }}</p>
      </div>
    </div>

    <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
      <div class="bg-green-500 rounded-md px-4 py-3">
        <i class="fa fa-check-circle fa-2x" aria-hidden="true" />
      </div>
      <div>
        <p class="text-lg font-medium">Leave Approved</p>
        <p class="text-center">{{ props.stats.leaveApproved }}</p>
      </div>
    </div>

    <div class="bg-white rounded-md flex items-center gap-4 shadow w-64">
      <div class="bg-red-500 rounded-md px-4 py-3">
        <i class="fa fa-times-circle fa-2x" aria-hidden="true" />
      </div>
      <div>
        <p class="text-lg font-medium">Leave Rejected</p>
        <p class="text-center">{{ props.stats.leaveRejected }}</p>
      </div>
    </div>

  </div>
</template>