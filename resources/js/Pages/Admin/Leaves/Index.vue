<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <h1 class="text-center text-3xl font-bold mb-12">Your Leave Schedules</h1>

    <input type="search" v-model="searchQuery" placeholder="Search By Employee ID" class="bg-white p-2 border border-gray-300 rounded-md
           focus:outline-none focus:border-blue-400 flex justify-start mb-6" />

    <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
        <thead class="border-slate-200 text-slate-600">
            <tr class="bg-gray-400 text-black font-medium">
                <th class="p-6">Serial No</th>
                <th class="p-6">Reason</th>
                <th class="p-6">Date</th>
                <th class="p-6">Status</th>
                <th class="p-6">Action</th>
            </tr>
        </thead>

        <tbody>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">1</td>
                <td class="p-4">Workmate's birthday</td>
                <td class="p-4">1/30/2026 to 2/1/2026</td>
                <td class="p-4">
                    <button class="bg-yellow-400 rounded-sm px-2 py-1">Pending</button>
                </td>
                <td class="p-4">
                    <Link href="/view-leave" class="bg-blue-400 rounded-sm px-2 py-1">View</Link>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">2</td>
                <td class="p-4">Hangover</td>
                <td class="p-4">2/2/2026 to 3/5/2026</td>
                <td class="p-4">
                    <button class="bg-red-500 rounded-sm px-2 py-1">Rejected</button>
                </td>
                <td class="p-4">
                    <Link href="/view-leave" class="bg-blue-400 rounded-sm px-2 py-1">View</Link>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">3</td>
                <td class="p-4">Sick</td>
                <td class="p-4">3/10/2026 to 3/12/2026</td>
                <td class="p-4">
                    <button class="bg-green-500 rounded-sm px-2 py-1">Accepted</button>
                </td>
                <td class="p-4">
                    <Link href="/view-leave" class="bg-blue-400 rounded-sm px-2 py-1">View</Link>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">4</td>
                <td class="p-4">Vacation</td>
                <td class="p-4">3/15/2026 to 4/20/2026</td>
                <td class="p-4">
                    <button class="bg-red-500 rounded-sm px-2 py-1">Rejected</button>
                </td>
                <td class="p-4">
                    <Link href="/view-leave" class="bg-blue-400 rounded-sm px-2 py-1">View</Link>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">5</td>
                <td class="p-4">Hospitalized</td>
                <td class="p-4">4/21/2026 to 5/10/2026</td>
                <td class="p-4">
                    <button class="bg-green-500 rounded-sm px-2 py-1">Accepted</button>
                </td>
                <td class="p-4">
                    <Link href="/view-leave" class="bg-blue-400 rounded-sm px-2 py-1">View</Link>
                </td>
            </tr>
        </tbody>
    </table>
</template>