<script setup>
import { reactive, onMounted, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const props = defineProps({
  employee: {
    type: Object,
    required: true
  },
  departments: {
    type: Array,
    default: () => []
  }
})

const page = usePage()
const showSuccess = ref(false)

// Split the name back into first and last name
const nameParts = props.employee?.name ? props.employee.name.split(' ') : ['', '']

const form = reactive({
  first_name: nameParts[0] || '',
  last_name: nameParts.slice(1).join(' ') || '',
  email: props.employee?.email || '',
  phone: props.employee?.phone || '',
  employee_id: props.employee?.employee_id || '',
  dob: props.employee?.dob || '',
  gender: props.employee?.gender || '',
  civil_status: props.employee?.civil_status || '',
  department_id: props.employee?.department_id || '',
  hire_date: props.employee?.hire_date || '',
  salary: props.employee?.salary || '',
  role: props.employee?.role || ''
})

const submit = () => {
  console.log('Submitting form...', form)
  
  router.put(`/edit/${props.employee.id}`, form, {
    preserveScroll: true,
    onSuccess: () => {
      alert('✅ Employee updated successfully!')
      showSuccess.value = true
      setTimeout(() => {
        showSuccess.value = false
        // Redirect to manage employees page after showing notification
        router.visit('/manageemployees')
      }, 2000)
    },
    onError: (errors) => {
      console.log('Validation errors:', errors)
      alert('❌ Error: ' + JSON.stringify(errors))
    },
    onFinish: () => {
      console.log('Request finished')
    }
  })
}

onMounted(() => {
  console.log('Employee data:', props.employee)
  console.log('Departments:', props.departments)
  console.log('Form data:', form)
})
</script>

<template>
  <!-- Main Content -->
  <main class="flex-1 p-8">
    <!-- Success Notification -->
    <div 
      v-if="showSuccess"
      class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 animate-fade-in"
    >
      <div class="flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span class="font-semibold">Employee updated successfully!</span>
      </div>
    </div>

    <div class="bg-white border-4 border-yellow-400 rounded-lg p-8 max-w-5xl mx-auto">

      <h1 class="text-2xl font-bold mb-6">
        Edit Employee
      </h1>

      <form @submit.prevent="submit" class="grid grid-cols-2 gap-6">

        <!-- First Name -->
        <div>
          <label class="text-sm font-semibold">First Name</label>
          <input
            v-model="form.first_name"
            type="text"
            placeholder="Enter First Name"
            class="w-full border rounded px-3 py-2 mt-1"
            required
          />
        </div>

        <!-- Last Name -->
        <div>
          <label class="text-sm font-semibold">Last Name</label>
          <input
            v-model="form.last_name"
            type="text"
            placeholder="Enter Last Name"
            class="w-full border rounded px-3 py-2 mt-1"
            required
          />
        </div>

        <!-- Email -->
        <div>
          <label class="text-sm font-semibold">Email</label>
          <input
            v-model="form.email"
            type="email"
            placeholder="Enter Email"
            class="w-full border rounded px-3 py-2 mt-1"
            required
          />
        </div>

        <!-- Phone -->
        <div>
          <label class="text-sm font-semibold">Phone</label>
          <input
            v-model="form.phone"
            type="text"
            placeholder="Enter Phone"
            class="w-full border rounded px-3 py-2 mt-1"
            required
          />
        </div>

        <!-- Employee ID -->
        <div>
          <label class="text-sm font-semibold">Employee ID</label>
          <input
            v-model="form.employee_id"
            type="text"
            placeholder="Enter ID"
            class="w-full border rounded px-3 py-2 mt-1"
            required
          />
        </div>

        <!-- Date of Birth -->
        <div>
          <label class="text-sm font-semibold">Date of Birth</label>
          <input
            v-model="form.dob"
            type="date"
            class="w-full border rounded px-3 py-2 mt-1"
            required
          />
        </div>

        <!-- Gender -->
        <div>
          <label class="text-sm font-semibold">Gender</label>
          <select
            v-model="form.gender"
            class="w-full border rounded px-3 py-2 mt-1"
            required
          >
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </div>

        <!-- Civil Status -->
        <div>
          <label class="text-sm font-semibold">Civil Status</label>
          <select
            v-model="form.civil_status"
            class="w-full border rounded px-3 py-2 mt-1"
            required
          >
            <option value="">Select Status</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
          </select>
        </div>

        <!-- Department -->
        <div>
          <label class="text-sm font-semibold">Department</label>
          <select
            v-model="form.department_id"
            class="w-full border rounded px-3 py-2 mt-1"
            required
          >
            <option value="">Select Department</option>
            <option 
              v-for="dept in departments" 
              :key="dept.id" 
              :value="dept.id"
            >
              {{ dept.name }}
            </option>
          </select>
        </div>

        <!-- Hire Date -->
        <div>
          <label class="text-sm font-semibold">Hire Date</label>
          <input
            v-model="form.hire_date"
            type="date"
            class="w-full border rounded px-3 py-2 mt-1"
            required
          />
        </div>

        <!-- Salary -->
        <div>
          <label class="text-sm font-semibold">Salary</label>
          <input
            v-model="form.salary"
            type="number"
            placeholder="Salary"
            class="w-full border rounded px-3 py-2 mt-1"
            required
          />
        </div>

        <!-- Role -->
        <div>
          <label class="text-sm font-semibold">Role</label>
          <select
            v-model="form.role"
            class="w-full border rounded px-3 py-2 mt-1"
            required
          >
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="hr">HR</option>
            <option value="employee">Employee</option>
          </select>
        </div>

        <!-- Button -->
        <div class="col-span-2 mt-6">
          <button
            type="submit"
              
          >
            Update Employee
          </button>
        </div>
           <!-- Back button -->
      <div class="mt-8">
        <Link
          href="/manageemployees"
          class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md"
        >
          Back to Employees
        </Link>
      </div>
      </form>
    </div>
  </main>
</template> 

