<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  employees: Object
})

</script>

<template>
  <div class="flex flex-col h-screen">
    <main class="flex-1 p-8 bg-gray-100 overflow-y-auto">

      <!-- Title -->
      <h1 class="text-3xl font-bold text-center mb-6">
        Manage Employees
      </h1>

      <!-- Search + Button -->
      <div  class="flex justify-between items-center mb-4">
        <input
          v-model="search"
          type="text"
          placeholder="Search by Employee ID"
          class="px-4 py-2 border rounded-md w-64 focus:ring focus:outline-none"
        />
      <div class="text-bold bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-md">
            <Link href="/addnewemployee"><i aria-hidden="true" /> Add New Employee</Link>
          </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-left">
          <thead class="bg-gray-200">
            <tr>
              <th class="px-6 py-3">ID</th>
                <th class="px-6 py-3">Name</th>
              <th class="px-6 py-3">Birth</th>
              <th class="px-6 py-3">Department</th>
              <th class="px-6 py-3 text-center">Action</th>
            </tr>
          </thead>

          <tbody>
            <tr
              
            >
              <td class="px-6 py-3">1</td>
               <td class="px-6 py-3">2</td>
              <td class="px-6 py-3">3</td>
              <td class="px-6 py-3">4</td>

            <td class="py-4 px-6">
            <div class="flex justify-center gap-2">
             <div class="bg-blue-500 hover:bg-blue-500 text-white px-5 py-2 rounded-md  inline-block">
             <Link href="/view">
             View
           </Link></div>

          <div class="bg-yellow-500 hover:bg-yellow-500 text-white px-5 py-2 rounded-md inline-block">
           <Link href="/edit">
            Edit
          </Link>
          </div>

           <div class="bg-red-500 hover:bg-red-500 text-white px-5 py-2 rounded-md inline-block">
          <Link href="/delete">
           delete
          </Link>
        </div>
</div>
</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-end items-center mt-4 space-x-2">
        <button class="w-8 h-8 flex items-center justify-center bg-gray-800 text-white rounded-full">
          ‹
        </button>
        <span class="font-semibold">1</span>
        <span class="text-gray-500">2</span>
        <span class="text-gray-500">3</span>
        <button class="w-8 h-8 flex items-center justify-center bg-gray-800 text-white rounded-full">
          ›
        </button>
      </div>

    </main>
  </div>
</template>
