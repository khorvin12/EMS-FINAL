<template>
  <div class="flex flex-col h-screen bg-gray-200">

    <!-- TOP BAR -->
    <header class="bg-green-500 w-full px-6 py-4 flex justify-between items-center shadow">
      <h2 class="text-white text-xl font-semibold">Admin</h2>

      <form @submit.prevent="logout">
        <button
          class="flex items-center bg-white text-black px-4 py-2 rounded shadow hover:bg-gray-100"
        >
          <span class="mr-2">👤</span> Logout
        </button>
      </form>
    </header>

    <!-- BODY -->
    <div class="flex flex-1">

      <!-- SIDEBAR -->
      <aside class="w-64 bg-gray-800 text-white flex flex-col py-6">
        <h1 class="text-3xl font-bold px-6 mb-8">Dashboard</h1>

        <nav class="flex flex-col space-y-2">
          <NavItem icon="🏠" label="Dashboard" :active="true" />
          <NavItem icon="👥" label="Employees" />
          <NavItem icon="🏢" label="Department" />
          <NavItem icon="📅" label="Leaves" />
          <NavItem icon="💵" label="Salary" />
          <NavItem icon="⚙️" label="Settings" />
        </nav>
      </aside>

      <!-- MAIN CONTENT -->
      <main class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-3xl font-bold mb-6">Dashboard Overview</h1>

        <!-- TOP CARDS -->
        <div class="grid grid-cols-3 gap-6 mb-12">
          <DashCard icon="👥" title="Total Employees" value="4" />
          <DashCard icon="🏢" title="Total Departments" value="4" />
          <DashCard icon="💵" title="Monthly Pay" value="$99999" />
        </div>

        <!-- LEAVE DETAILS -->
        <h2 class="text-2xl font-bold text-center mb-6">Leave Details</h2>

        <div class="grid grid-cols-2 gap-6">
          <LeaveCard color="bg-green-500" icon="📄" label="Leave Applied" value="4" />
          <LeaveCard color="bg-green-400" icon="✔️" label="Leave Approved" value="4" />
          <LeaveCard color="bg-yellow-400" icon="⏳" label="Leave Pending" value="4" />
          <LeaveCard color="bg-red-500" icon="❌" label="Leave Rejected" value="4" />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

const logout = () => {
  router.post('/logout')
}

const NavItem = {
  props: ['icon', 'label', 'active'],
  template: `
    <div
      class="flex items-center px-6 py-3 text-lg cursor-pointer"
      :class="active ? 'bg-green-500 text-white rounded-r-full' : 'hover:bg-gray-700'"
    >
      <span class="mr-3 text-xl">{{ icon }}</span>
      <span>{{ label }}</span>
    </div>
  `
}

const DashCard = {
  props: ['icon', 'title', 'value'],
  template: `
    <div class="bg-white p-6 rounded-xl shadow flex items-center space-x-4">
      <div class="text-3xl">{{ icon }}</div>
      <div>
        <p class="text-gray-600 text-sm">{{ title }}</p>
        <p class="text-2xl font-bold">{{ value }}</p>
      </div>
    </div>
  `
}

const LeaveCard = {
  props: ['color', 'icon', 'label', 'value'],
  template: `
    <div class="bg-white p-6 rounded-xl shadow flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <div
          class="w-12 h-12 rounded-full flex items-center justify-center text-2xl text-white"
          :class="color"
        >
          {{ icon }}
        </div>

        <div>
          <p class="font-semibold">{{ label }}</p>
          <p class="text-xl font-bold">{{ value }}</p>
        </div>
      </div>
    </div>
  `
}
</script>

<style scoped>
::-webkit-scrollbar {
  width: 6px;
}
::-webkit-scrollbar-thumb {
  background: #bbb;
  border-radius: 3px;
}
</style>
