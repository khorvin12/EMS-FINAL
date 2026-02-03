<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <!-- Title -->
  <h1 class="text-center text-3xl font-bold mb-12">Manage Employees</h1>

  <!-- Search + Button -->
  <div class="flex justify-between mb-6">
    <input type="search" v-model="searchQuery" placeholder="Search By Employee ID" class="bg-white p-2 border border-gray-300 rounded-md
           focus:outline-none focus:border-blue-400" />

    <div class="text-bold bg-green-500 hover:bg-green-600 text-black px-4 py-2 rounded-md">
      <Link href="/addnewemployee"><i aria-hidden="true" /> Add New Employee</Link>
    </div>
  </div>

  <!-- Table -->
  <table class="w-full rounded-lg shadow-lg overflow-hidden table-auto bg-white">
    <thead class="bg-gray-400 text-black font-medium">
      <tr>
        <th class="py-6">Employee ID</th>
        <th class="py-6">Firstname</th>
        <th class="py-6">Lastname</th>
        <th class="py-6">Email</th>
        <th class="py-6">Phone</th>
        <th class="py-6">Department</th>
        <th class="py-6">Role</th>
        <th class="py-6">Hire Date</th>
        <th class="py-6">Salary</th>
        <th class="py-6 text-center">Action</th>
      </tr>
    </thead>

    <tbody class="bg-white-100 text-center border-slate-200 border-t-4">
      <tr>
        <td class="p-4">12345</td>
        <td class="p-4">Khorvin</td>
        <td class="p-4">Gay</td>
        <td class="p-4">KhorvinGay@gmail.com</td>
        <td class="p-4">09876543210</td>
        <td class="p-4">IT</td>
        <td class="p-4">Employee</td>
        <td class="p-4">2050</td>
        <td class="p-4">10,000</td>

        <td class="p-4">
          <div class="flex justify-center gap-2">
            <div class="bg-blue-500 hover:bg-blue-600 text-black px-5 py-2 rounded-md  inline-block">
              <Link href="/view">
                View
              </Link>
            </div>

            <div class="bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-2 rounded-md inline-block">
              <Link href="/edit">
                Edit
              </Link>
            </div>

            <div class="bg-red-500 hover:bg-red-600 text-black px-4 py-2 rounded-md inline-block">
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