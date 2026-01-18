<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <h1 class="text-center text-3xl font-bold mb-12">January 2026</h1>

    <div class="flex justify-between mb-6">
        <div class="bg-white rounded-md px-4 py-2">
            <input type="search" placeholder="Search By Employee ID" />
        </div>
    </div>

    <table class="w-full rounded-md shadow-lg overflow-hidden mb-6 table-fixed bg-white">
        <thead class="border-b border-slate-100 text-slate-600 text-center">
            <tr class="bg-gray-400 text-black font-medium">
                <th class="px-6 py-4">ID</th>
                <th>Name</th>
                <th>Pay Period</th>
                <th>Full Salary</th>
                <th>Deduction</th>
                <th>Salary</th>
            </tr>
        </thead>

        <tbody>
            <tr class="bg-white-100 w-full p-0 text-center">
                <td class="px-6 py-4 border-b border-slate-100">1</td>
                <td class="px-6 py-4 border-b border-slate-100">James</td>
                <td class="px-6 py-4 border-b border-slate-100">January</td>
                <td class="px-6 py-4 border-b border-slate-100">$12,000,000</td>
                <td class="px-6 py-4 border-b border-slate-100">$15,000,000</td>
                <td class="px-6 py-4 border-b border-slate-100 flex justify-center">
                    <p class="ml-15 mr-4 font-bold">$1,000,000</p>
                    <div>
                        <Link href="/editdepartment" class="bg-yellow-300 rounded-sm px-4 py-1">Edit</Link>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>