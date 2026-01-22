<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <h1 class="text-center text-3xl font-bold mb-12">Your Attendance</h1>

    <div class="flex justify-between mb-6">
        <div class="bg-white rounded-md p-2">
            <input type="search" placeholder="Search By SNO" />
        </div>
    </div>

    <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
        <thead class="bg-gray-400 text-black font-medium">
            <tr class="">
                <th class="py-6">SNO</th>
                <th class="">Employee ID</th>
                <th class="">Date</th>
                <th class="">Time In</th>
                <th class="">Time Out</th>
                <th class="">Hours</th>
                <th class="">Status</th>
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
                <td class="p-4">3</td>
                <td class="p-4">33333</td>
                <td class="p-4">3/10/2026</td>
                <td class="p-4">--</td>
                <td class="p-4">--</td>
                <td class="p-4">0</td>
                <td class="p-4">
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