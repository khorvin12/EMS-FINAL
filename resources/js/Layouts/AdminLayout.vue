<script setup>
import { Link, router } from '@inertiajs/vue3'

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
          <div class="rounded-md px-10 py-2 hover:bg-red-700">
            <Link href="/admin-dashboard"><i class="fa fa-tachometer" aria-hidden="true" /> Dashboard</Link>
          </div>

          <div class="rounded-md p-2 text-center hover:bg-red-700">
            <Link href="/manageemployees"><i class="fa fa-users" aria-hidden="true" /> Employees</Link>
          </div>

          <div class="rounded-md p-2 text-center hover:bg-red-700">
            <Link href="/departments"><i class="fa fa-building" aria-hidden="true" /> Departments</Link>
          </div>

          <div class="rounded-md p-2 text-center hover:bg-red-700">
            <Link href="/leaves"><i class="fa fa-calendar" aria-hidden="true" /> Leaves</Link>
          </div>

          <div class="rounded-md p-2 text-center hover:bg-red-700">
            <Link href="/settings"><i class="fa fa-cog" aria-hidden="true" /> Settings</Link>
          </div>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 overflow-y-auto bg-slate-200 p-8">
        <slot />
      </main>
    </div>
  </div>
</template>