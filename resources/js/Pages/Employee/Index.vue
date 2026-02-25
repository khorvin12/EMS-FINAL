<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { usePage, router } from '@inertiajs/vue3'

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

// Get the profile picture path with storage URL
const profilePicture = computed(() => {
  if (user.value?.profile_picture) {
    return `/storage/${user.value.profile_picture}`
  }
  return null
})

const fileInput = ref(null)

const fetchDashboardStats = async () => {
    try {
        const response = await axios.get('/api/dashboard/stats');
        stats.value = response.data;
    } catch (error) {
        console.error('Error fetching dashboard stats:', error);
    }
};

const handleFileUpload = (event) => {
    const file = event.target.files[0]
    if (file) {
        console.log('Uploading file:', file.name)
        
        const formData = new FormData()
        formData.append('profile_picture', file)
        
        router.post('/employee/upload-profile-picture', formData, {
            preserveScroll: true,
            onSuccess: (page) => {
                console.log('Upload successful!')
                // Force page reload to get updated user data
                router.reload({ only: ['auth'] })
            },
            onError: (errors) => {
                console.error('Upload failed:', errors)
            }
        })
    }
}

const openFileDialog = () => {
    fileInput.value.click()
}

// Load data on component mount
onMounted(() => {
    fetchDashboardStats();
});
</script>

<template>
  <!-- First Row -->
  <div class="p-4">
    <div class="flex items-center gap-3">
      <!-- Profile Picture -->
      <div class="relative group cursor-pointer" @click="openFileDialog">
        <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center shadow overflow-hidden">
          <img v-if="profilePicture" :src="profilePicture" alt="Profile" class="w-full h-full object-cover" />
          <i v-else class="fa fa-user fa-lg text-gray-600"></i>
        </div>
        <!-- Hover overlay -->
        <div class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
          <i class="fa fa-camera text-white text-sm"></i>
        </div>
      </div>
      <input 
        ref="fileInput" 
        type="file" 
        accept="image/*" 
        @change="handleFileUpload" 
        class="hidden"
      />
      <h1 class="text-2xl font-bold">Profile: {{ user.name }}</h1>
    </div>
    <p class="mt-10 text-center font-bold text-2xl mb-4">Dashboard Overview</p>
    <div class="mb-4 flex justify-between items-center">
      <!-- Total Employees Card -->
      <div class="bg-white rounded-md flex items-center gap-2 shadow-md p-1 border-l-4 border-green-500">
        <div class="bg-green-500 rounded-md px-3 py-3">
          <i class="fa fa-users fa-2x" />
        </div>
        <div>
          <p class="font-bold">Total Employees:</p>
          <p class="font-bold text-center mr-50">{{ stats.totalEmployees }}</p>
        </div>
      </div>
      
      <!-- Total Departments Card --> 
      <div class="bg-white rounded-md flex items-center gap-2 shadow-md p-1 border-l-4 border-green-500">
        <div class="bg-green-500 rounded-md px-3 py-3">
          <i class="fa fa-building fa-2x" />
        </div>
        <div>
          <p class="font-bold">Total Departments:</p>
          <p class="font-bold text-center mr-50">{{ stats.totalDepartments }}</p>
        </div>
      </div>
      
      <!-- Monthly Pay Card -->
      <div class="bg-white rounded-md flex items-center gap-2 shadow-md p-1 border-l-4 border-green-500 group">
        <div class="bg-green-500 rounded-md px-3 py-3">
          <i class="fa fa-money-bill fa-2x" />
        </div>
        <div>
          <p class="font-bold">Monthly Pay:</p>
          <p class="font-bold text-center mr-50 cursor-pointer">
            <h1 class="font-bold text-gray-400 group-hover:hidden"><i class="fa fa-eye-slash"></i> Hidden</h1>
            <Link href="/employee/payroll" class="font-bold hidden group-hover:inline text-green-600 cursor-pointer">$9,000</Link>
          </p>
        </div>
      </div>
    </div>
  </div>

  <h1 class="text-2xl font-bold mb-4 text-center mt-30">Leave Review</h1>

  <!-- Second Row -->
  <div class="p-4">
    <div class="mb-4 flex justify-between items-center">
      <!-- Leave Pending Card -->
      <div class="bg-white rounded-md flex items-center gap-2 shadow-md p-1 border-l-4 border-yellow-500">
        <div class="bg-yellow-500 rounded-md px-3 py-3">
          <i class="fa fa-clock fa-2x" />
        </div>
        <div>
          <p class="font-bold">Leave Pending:</p>
          <p class="font-bold text-center mr-50">{{ stats.leavePending }}</p>
        </div>
      </div>
      
      <!-- Leave Approved Card --> 
      <div class="bg-white rounded-md flex items-center gap-2 shadow-md p-1 border-l-4 border-green-500">
        <div class="bg-green-500 rounded-md px-3 py-3">
          <i class="fa fa-check-circle fa-2x" />
        </div>
        <div>
          <p class="font-bold">Leave Approved:</p>
          <p class="font-bold text-center mr-50">{{ stats.leaveApproved }}</p>
        </div>
      </div>
      
      <!-- Leave Rejected Card -->
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