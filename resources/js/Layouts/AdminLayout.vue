<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'

const page = usePage()

const isActive = (path) => page.url.startsWith(path)

function logout() {
  router.post('/logout')
}
</script>

<template>
  <div class="flex flex-col h-screen">
    <!-- Top Bar-->
    <header class="bg-red-800 text-white">
      <nav class="flex items-center justify-between text-3xl p-6 font-bold">
        <h1 class="ml-14">Admin</h1>

        <!-- Logout -->
        <button @click="logout" class="text-white font-bold">
          Logout
        </button>
      </nav>
    </header>

    <div class="flex flex-1">
      <!-- Side Bar -->
      <aside class="bg-gray-800 text-white flex justify-center w-64 py-10 text-xl">
        <nav class="space-y-8">
          <Link href="/dashboard" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/dashboard')
            ? 'bg-red-600 font-bold'
            : 'hover:bg-red-700'">
            <i class="fa fa-tachometer" aria-hidden="true"></i>
            <span>Dashboard</span>
          </Link>

          <Link href="/manageemployees" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/manageemployees')
            ? 'bg-red-600 font-bold'
            : 'hover:bg-red-700'">
            <i class="fa fa-users" aria-hidden="true"></i>
            <span>Employees</span>
          </Link>

          <Link href="/departments" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/departments')
            ? 'bg-red-600 font-bold'
            : 'hover:bg-red-700'">
            <i class="fa fa-building" aria-hidden="true"></i>
            <span>Departments</span>
          </Link>

          <Link href="/leaves" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/leaves')
            ? 'bg-red-600 font-bold'
            : 'hover:bg-red-700'">
            <i class="fa fa-calendar-times-o" aria-hidden="true">&#xf273;</i>
            <span>Leaves</span>
          </Link>

          <Link href="/settings" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/settings')
            ? 'bg-red-600 font-bold'
            : 'hover:bg-red-700'">
            <i class="fa fa-cog" aria-hidden="true"></i>
            <span>Settings</span>
          </Link>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 overflow-y-auto bg-slate-200 p-8">
        <slot />
      </main>
    </div>
  </div>
</template>