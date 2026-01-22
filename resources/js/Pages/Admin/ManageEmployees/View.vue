<script setup>
defineProps({
  employee: {
    type: Object,
    required: true
  }
})
</script>

<template>
  <main class="flex-1 p-8">
    <div class="bg-white border-4 border-blue-400 rounded-lg p-8 max-w-5xl mx-auto">

      <h1 class="text-2xl font-bold mb-6">
        View Employee
      </h1>

      <div class="grid grid-cols-2 gap-6 text-gray-800">

        <div>
          <p class="text-sm font-bold">Employee ID</p>
          <p class="mt-1">{{ employee.employee_id }}</p>
        </div>

        <div>
          <p class="text-sm font-bold">Name</p>
          <p class="mt-1">{{ employee.name }}</p>
        </div>

        <div>
          <p class="text-sm font-bold">Email</p>
          <p class="mt-1">{{ employee.email }}</p>
        </div>

        <div>
          <p class="text-sm font-bold">Phone</p>
          <p class="mt-1">{{ employee.phone }}</p>
        </div>

        <div>
          <p class="text-sm font-bold">Department</p>
          <p class="mt-1">{{ employee.department?.name ?? 'N/A' }}</p>
        </div>

        <div>
          <p class="text-sm font-bold">Role</p>
          <p class="mt-1 capitalize">{{ employee.role }}</p>
        </div>

        <div>
          <p class="text-sm font-bold">Date of Birth</p>
          <p class="mt-1">{{ employee.dob }}</p>
        </div>

        <div>
          <p class="text-sm font-bold">Gender</p>
          <p class="mt-1">{{ employee.gender }}</p>
        </div>

        <div>
          <p class="text-sm font-bold">Civil Status</p>
          <p class="mt-1">{{ employee.civil_status }}</p>
        </div>

        <div>
          <p class="text-sm font-bold">Hire Date</p>
          <p class="mt-1">{{ employee.hire_date }}</p>
        </div>

        <div>
          <p class="text-sm font-bold">Salary</p>
          <p class="mt-1">₱{{ Number(employee.salary).toLocaleString() }}</p>
        </div>

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

    </div>
  </main>
</template>
