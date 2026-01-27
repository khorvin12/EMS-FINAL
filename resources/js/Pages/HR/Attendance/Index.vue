<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <h1 class="text-center text-3xl font-bold mb-12">Date</h1>

    <div class="flex justify-between mb-6">
        <div class="bg-white rounded-md p-2">
            <input type="search" placeholder="Search By Serial No." />
        </div>

        <div  class="bg-green-500 text-black px-4 py-2 rounded-md hover:bg-green-400">
            <Link href="/view-attendance">Attendance Report</Link>
        </div>
    </div>

    <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
        <thead>
            <tr class="bg-gray-400 text-black font-medium text-lg">
                <th class="p-6">Serial No.</th>
                <th class="p-6">Employee ID</th>
                <th class="p-6">Name</th>
                <th class="p-6">Date</th>
                <th class="p-6">Time In</th>
                <th class="p-6">Time Out</th>
                <th class="p-6">Status</th>
                <th class="p-6">Action</th>
            </tr>
        </thead>

        <tbody>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">1</td>
                <td class="p-4">11111</td>
                <td class="p-4">Kier</td>
                <td class="p-4">1/30/2026</td>
                <td class="p-4">8:00 AM</td>
                <td class="p-4">5:00 PM</td>
                <td class="p-4">
                    <button>Present</button>
                </td>
                <td class="p-4 space-x-4 inline-flex">

                    <Link href="/edit" class="bg-yellow-400 hover:bg-yellow-300 rounded-sm px-4 py-1">Edit</Link>

                    <button class="bg-red-500 hover:bg-red-400 text-black rounded-sm px-2 py-1">Delete</button>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">2</td>
                <td class="p-4">22222</td>
                <td class="p-4">Lucky Jade</td>
                <td class="p-4">1/30/2026</td>
                <td class="p-4">9:00 AM</td>
                <td class="p-4">5:00 PM</td>
                <td class="p-4">
                    <button>Late</button>
                </td>
                <td class="p-4 space-x-4 inline-flex">

                    <Link href="/edit" class="bg-yellow-400 hover:bg-yellow-300 rounded-sm px-4 py-1">Edit</Link>

                    <button class="bg-red-500 hover:bg-red-400 text-black rounded-sm px-2 py-1">Delete</button>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">3</td>
                <td class="p-4">33333</td>
                <td class="p-4">Mark</td>
                <td class="p-4">
                    <input type="date" name="date" class="bg-gray-200 rounded-md px-2 py-1">
                </td>
                <td class="p-4">
                    <input type="time" name="timein" class="bg-gray-200 rounded-md px-2 py-1">
                </td>
                <td class="p-4">
                    <input type="time" name="timeout" class="bg-gray-200 rounded-md px-2 py-1">
                </td>
                <td class="p-4">
                    <button>Absent</button>
                </td>
                <td class="p-4 space-x-4 inline-flex">

                    <Link href="/edit" class="bg-yellow-400 hover:bg-yellow-300 rounded-sm px-4 py-1">Edit</Link>

                    <button class="bg-red-500 hover:bg-red-400 text-black rounded-sm px-2 py-1">Delete</button>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">4</td>
                <td class="p-4">44444</td>
                <td class="p-4">Khorvin</td>
                <td class="p-4">
                    <input type="date" name="date" class="bg-gray-200 rounded-md px-2 py-1">
                </td>
                <td class="p-4">
                    <input type="time" name="timein" class="bg-gray-200 rounded-md px-2 py-1">
                </td>
                <td class="p-4">
                    <input type="time" name="timeout" class="bg-gray-200 rounded-md px-2 py-1">
                </td>
                <td class="p-4">
                    <button>On Leave</button>
                </td>
                <td class="p-4 space-x-4 inline-flex">

                    <Link href="/edit" class="bg-yellow-400 hover:bg-yellow-300 rounded-sm px-4 py-1">Edit</Link>
                    <button class="bg-red-500 hover:bg-red-400 text-black rounded-sm px-2 py-1">Delete</button>
                </td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">5</td>
                <td class="p-4">55555</td>
                <td class="p-4">Nico</td>
                <td class="p-4">
                    <input type="date" name="date" class="bg-gray-200 rounded-md px-2 py-1">
                </td>
                <td class="p-4">
                    <input type="time" name="timein" class="bg-gray-200 rounded-md px-2 py-1">
                </td>
                <td class="p-4">
                    <input type="time" name="timeout" class="bg-gray-200 rounded-md px-2 py-1">
                </td>
                <td class="p-4">
                    <button>On Leave</button>
                </td>
                <td class="p-4 space-x-4 inline-flex">

                    <Link href="/edit" class="bg-yellow-400 hover:bg-yellow-300 rounded-sm px-4 py-1">Edit</Link>
                    <button class="bg-red-500 hover:bg-red-400 text-black rounded-sm px-2 py-1">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>