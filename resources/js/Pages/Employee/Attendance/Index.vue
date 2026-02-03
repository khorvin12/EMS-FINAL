<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    
    <h1 class="text-center text-3xl font-bold mb-12">Your Attendance</h1>

    <input type="search" v-model="searchQuery" placeholder="Search By Employee ID" class="bg-white p-2 border border-gray-300 rounded-md
           focus:outline-none focus:border-blue-400 flex justify-start mb-6" />

    <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
        <thead class="bg-gray-400 text-black font-medium">
            <tr>
                <th class="py-6">Serial No</th>
                <th class="py-6">Employee ID</th>
                <th class="py-6">Date</th>
                <th class="py-6">Time In</th>
                <th class="py-6">Time Out</th>
                <th class="py-6">Hours</th>
                <th class="py-6">Status</th>
            </tr>
        </thead>

        <tbody>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">1</td>
                <td class="p-4">11111</td>
                <td class="p-4">1/30/2026</td>
                <td class="p-4">8:00 AM</td>
                <td class="p-4">5:00 PM</td>
                <td class="p-4">9</td>
                <td class="p-4">
                    <button class="bg-green-500 rounded-sm px-2 py-1">Present</button>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">2</td>
                <td class="p-4">22222</td>
                <td class="p-4">2/20/2026</td>
                <td class="p-4">9:00 AM</td>
                <td class="p-4">5:00 PM</td>
                <td class="p-4">8</td>
                <td class="p-4">
                    <button class="bg-yellow-300 rounded-sm px-2 py-1">Late</button>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="py-4">3</td>
                <td class="py-4">33333</td>
                <td class="py-4">3/10/2026</td>
                <td class="py-4">--</td>
                <td class="py-4">--</td>
                <td class="py-4">0</td>
                <td class="py-4">
                    <button class="bg-red-500 rounded-sm px-2 py-1">Absent</button>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">4</td>
                <td class="p-4">44444</td>
                <td class="p-4">4/20/2026</td>
                <td class="p-4">--</td>
                <td class="p-4">--</td>
                <td class="p-4">0</td>
                <td class="p-4">
                    <button class="bg-blue-400 rounded-sm px-2 py-1">On Leave</button>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">5</td>
                <td class="p-4">55555</td>
                <td class="p-4">5/20/2026</td>
                <td class="p-4">10:00 AM</td>
                <td class="p-4">5:00 PM</td>
                <td class="p-4">7</td>
                <td class="p-4">
                    <button class="bg-green-500 rounded-sm px-2 py-1">Present</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>