<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <h1 class="text-center text-3xl font-bold mb-12">Manage Departments</h1>

    <div class="flex justify-between mb-6">
        <div class="bg-white rounded-md px-4 py-2">
            <input type="search" placeholder="Search By Department..." />
        </div>

        <div class="bg-green-500 hover:bg-green-600 rounded-md px-4 py-2">
            <Link href="/adddepartment">Add New Department</Link>
        </div>
    </div>

    <table class="w-full rounded-md shadow-lg overflow-hidden mb-6 table-auto bg-white">
        <thead class="border-b border-slate-100 text-slate-600 text-center">
            <tr class="bg-gray-400 text-black font-medium">
                <th class="px-6 py-4">ID No.</th>
                <th>Department</th>
                <th>Manager ID</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <tr class="bg-white-100 w-full p-0 text-center">
                <td class="px-6 py-4 border-b border-slate-100">1</td>
                <td class="px-6 py-4 border-b border-slate-100">2</td>
                <td class="px-6 py-4 border-b border-slate-100">3</td>
                <td class="px-6 py-4 border-b border-slate-100 space-x-4">
                    <Link href="/editdepartment" class="bg-yellow-300 rounded-sm px-4 py-1">Edit</Link>
                    <button class="bg-red-500 rounded-sm px-2 py-1">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>