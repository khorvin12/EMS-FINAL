<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <h1 class="text-center text-3xl font-bold mb-12">Your Leave Schedules</h1>

    <div class="flex justify-between mb-6">
        <div class="bg-white rounded-md p-2">
            <input type="search" placeholder="Search By SNO" />
        </div>

        <div class="bg-green-500 hover:bg-green-600 rounded-md px-4 py-2">
            <Link href="/create-leave">Add Leave</Link>
        </div>
    </div>

    <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
        <thead class="border-slate-200 text-slate-600">
            <tr class="bg-gray-400 text-black font-medium">
                <th class="p-6">SNO</th>
                <th class="">Reason</th>
                <th class="">Date</th>
                <th class="">Status</th>
                <th class="">Action</th>
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