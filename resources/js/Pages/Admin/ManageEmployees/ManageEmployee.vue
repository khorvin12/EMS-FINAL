<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <!-- Title -->
  <h1 class="text-center text-3xl font-bold mb-12">Manage Employees</h1>

  <!-- Search + Button -->
  <div class="flex justify-between mb-6">
    <div class="bg-white rounded-md p-2">
      <input type="search" v-model="searchQuery" placeholder="Search By Department" />
    </div>

    <div class="text-bold bg-green-600 hover:bg-green-500 text-black px-4 py-2 rounded-md">
      <Link href="/addnewemployee"><i aria-hidden="true" /> Add New Employee</Link>
    </div>
  </div>

  <!-- Table -->
    <table class="w-full rounded-lg shadow-lg overflow-hidden table-fixed bg-white">
      <thead class="bg-gray-400 text-black font-medium">
        <tr>
          <th class="py-6">ID</th>
          <th class="py-6">Birth</th>
          <th class="py-6">Department</th>
          <th class="py-6 text-center">Action</th>
        </tr>
      </thead>

      <tbody class="bg-white-100 text-center border-slate-200 border-t-4">
        <tr>
          <td class="py-4">1</td>
          <td class="py-4">2</td>
          <td class="py-4">3</td>

          <td class="p-4">
            <div class="flex justify-center gap-2">
              <div class="bg-blue-500 hover:bg-blue-400 text-black px-5 py-2 rounded-md  inline-block">
                <Link href="/view">
                  View
                </Link>
              </div>

              <div class="bg-yellow-400 hover:bg-yellow-300 text-black px-6 py-2 rounded-md inline-block">
                <Link href="/edit">
                  Edit
                </Link>
              </div>

              <div class="bg-red-600 hover:bg-red-500 text-black px-4 py-2 rounded-md inline-block">
                <Link href="/delete">
                  delete
                </Link>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

  <!-- Pagination -->
  <div class="flex justify-end items-center mt-4 space-x-2">
    <button class="w-8 h-8 bg-gray-800 text-white rounded-full">
      ‹
    </button>
    <span class="font-semibold">1</span>
    <span class="text-gray-500">2</span>
    <span class="text-gray-500">3</span>
    <button class="w-8 h-8 bg-gray-800 text-white rounded-full">
      ›
    </button>
  </div>
</template>