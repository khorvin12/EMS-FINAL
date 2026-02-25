<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = computed(() => page.props.auth?.user ?? null)

const stats = ref({
    totalEmployees: 4,
    totalDepartments: 0,
    monthlyPay: 0,
    leavePending: 0,
    leaveApproved: 0,
    leaveRejected: 0
});

const fetchDashboardStats = async () => {
    try {
        const response = await axios.get('/api/dashboard/stats');
        stats.value = response.data;
    } catch (error) {
        console.error('Error fetching dashboard stats:', error);
    }
};

// Load data on component mount
onMounted(() => {
    fetchDashboardStats();
});
</script>

<template>
  <!-- First Row -->
  <div class="p-4">
    <h1 class="text-2xl font-bold">HR: {{ user.name }}</h1>
    <p class="mt-10 text-center font-bold text-2xl mb-4">Dashboard Overview</p>
    <div class="mb-4 flex justify-between items-center">
      <!-- Total Employees Card -->
      <div class="bg-white rounded-md flex items-center gap-2 shadow-md p-1 border-l-4 border-blue-500">
                <div class="bg-blue-500 rounded-md px-3 py-3">
                    <i class="fa fa-users fa-2x" />
                </div>
                <div>
                    <p class="font-bold">Total Employees:</p>
                    <p class="font-bold text-center mr-50">{{ stats.totalEmployees }}</p>
                </div>
            </div>
      <!-- Total Departments Card --> 
      <div class="bg-white rounded-md flex items-center gap-2 shadow-md p-1 border-l-4 border-blue-500">
          <div class="bg-blue-500 rounded-md px-3 py-3">
            <i class="fa fa-building fa-2x" />
          </div>
        <div>
          <p class="font-bold">Total Departments:</p>
          <p class="font-bold text-center mr-50">{{ stats.totalDepartments }}</p>
        </div>
      </div>
      <!-- Monthly Pay Card -->
      <div class="bg-white rounded-md flex items-center gap-2 shadow-md p-1 border-l-4 border-blue-500">
          <div class="bg-blue-500 rounded-md px-3 py-3">
            <i class="fa fa-money-bill fa-2x" />
          </div>
        <div>
          <p class="font-bold">Monthly Pay:</p>
          <p class="font-bold text-center mr-50 text-gray-200"><i class="fa fa-eye-slash"></i> Hidden</p>
        </div>
      </div>
    </div>
  </div>

    <h1 class="text-2xl font-bold mb-4 text-center mt-30">Leave Review</h1>

  <!-- Second Row -->
    <div class="p-4">
    <div class="mb-4 flex justify-between items-center">
      <!-- Total Employees Card -->
      <div class="bg-white rounded-md flex items-center gap-2 shadow-md p-1 border-l-4 border-yellow-500">
                <div class="bg-yellow-500 rounded-md px-3 py-3">
                    <i class="fa fa-clock fa-2x" />
                </div>
                <div>
                    <p class="font-bold">Leave Pending:</p>
                    <p class="font-bold text-center mr-50">{{ stats.leavePending }}</p>
                </div>
            </div>
      <!-- Total Departments Card --> 
      <div class="bg-white rounded-md flex items-center gap-2 shadow-md p-1 border-l-4 border-green-500">
          <div class="bg-green-500 rounded-md px-3 py-3">
            <i class="fa fa-check-circle fa-2x" />
          </div>
        <div>
          <p class="font-bold">Leave Approved:</p>
          <p class="font-bold text-center mr-50">{{ stats.leaveApproved }}</p>
        </div>
      </div>
      <!-- Monthly Pay Card -->
      <div class="bg-white rounded-md flex items-center gap-2 shadow-md p-1 border-l-4 border-red-500">
          <div class="bg-red-500 rounded-md px-3 py-3">
            <i class="fa fa-times-circle fa-2x" />
          </div>
        <div>
          <p class="font-bold">Leave Rejected:</p>
          <p class="font-bold text-center mr-50">{{ stats.leaveRejected }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
