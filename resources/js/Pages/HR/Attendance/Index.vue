<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <h1 class="text-center text-3xl font-bold mb-12">Your Attendance</h1>

        <div class="flex justify-between mb-6">
            <input type="search" placeholder="Search By Name or ID"
                class="bg-white rounded-md p-2 border border-gray-300 focus:outline-none focus:border-blue-400" />

            <Link href="/attendance/create" class="bg-green-500 text-black px-4 py-2 rounded-md hover:bg-green-600">Add
                Attendance</Link>
        </div>


        <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg text-left">
            <thead>
                <tr class="bg-gray-400 text-black font-medium">
                    <th class="px-6 py-4">SNO</th>
                    <th class="px-6 py-4">Employee ID</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Time In</th>
                    <th class="px-6 py-4">Time Out</th>
                    <th class="px-6 py-4">Hours</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>

            <tbody>
                <tr class="bg-white-100 border-slate-200 border-t-4">
                    <td class="px-6 py-4">1</td>
                    <td class="px-6 py-4">11111</td>
                    <td class="px-6 py-4">1/30/2026</td>
                    <td class="px-6 py-4">8:00 AM</td>
                    <td class="px-6 py-4">5:00 PM</td>
                    <td class="px-6 py-4">9</td>
                    <td class="px-6 py-4">
                        <button class="bg-green-500 rounded-full px-4 py-1">Present</button>
                    </td>
                    <td class="px-6 py-4 space-x-4 inline-flex">

                        <Link href="/edit"
                            class="bg-yellow-400 hover:bg-yellow-500 rounded-lg px-4 py-2 text-sm font-semibold">Edit
                        </Link>

                        <button
                            class="bg-red-500 hover:bg-red-600 text-black rounded-lg px-4 py-2 text-sm font-semibold">Delete</button>
                    </td>
                </tr>
                <tr class="bg-white-100 border-slate-200 border-t-4">
                    <td class="px-6 py-4">2</td>
                    <td class="px-6 py-4">22222</td>
                    <td class="px-6 py-4">2/20/2026</td>
                    <td class="px-6 py-4">9:00 AM</td>
                    <td class="px-6 py-4">5:00 PM</td>
                    <td class="px-6 py-4">8</td>
                    <td class="px-6 py-4">
                        <button class="bg-yellow-400 rounded-full px-4 py-1">Late</button>
                    </td>
                    <td class="px-6 py-4 space-x-4 inline-flex">

                        <Link href="/edit"
                            class="bg-yellow-400 hover:bg-yellow-500 rounded-lg px-4 py-2 text-sm font-semibold">Edit
                        </Link>

                        <button
                            class="bg-red-500 hover:bg-red-600 text-black rounded-lg px-4 py-2 text-sm font-semibold">Delete</button>
                    </td>
                </tr>
                <tr class="bg-white-100 border-slate-200 border-t-4">
                    <td class="px-6 py-4">3</td>
                    <td class="px-6 py-4">33333</td>
                    <td class="px-6 py-4">3/10/2026</td>
                    <td class="px-6 py-4">--</td>
                    <td class="px-6 py-4">--</td>
                    <td class="px-6 py-4">0</td>
                    <td class="px-6 py-4">
                        <button class="bg-red-500 rounded-full px-4 py-1">Absent</button>
                    </td>
                    <td class="px-6 py-4 space-x-4 inline-flex">
                        <Link href="/edit"
                            class="bg-yellow-400 hover:bg-yellow-500 rounded-lg px-4 py-2 text-sm font-semibold">Edit
                        </Link>

                        <button
                            class="bg-red-500 hover:bg-red-600 text-black rounded-lg px-4 py-2 text-sm font-semibold">Delete</button>
                    </td>
                </tr>
                <tr class="bg-white-100 border-slate-200 border-t-4">
                    <td class="px-6 py-4">4</td>
                    <td class="px-6 py-4">44444</td>
                    <td class="px-6 py-4">4/20/2026</td>
                    <td class="px-6 py-4">--</td>
                    <td class="px-6 py-4">--</td>
                    <td class="px-6 py-4">0</td>
                    <td class="px-6 py-4">
                        <button class="bg-blue-400 rounded-full px-4 py-1">On Leave</button>
                    </td>
                    <td class="px-6 py-4 space-x-4 inline-flex">

                        <Link href="/edit"
                            class="bg-yellow-400 hover:bg-yellow-500 rounded-lg px-4 py-2 text-sm font-semibold">Edit
                        </Link>
                        <button
                            class="bg-red-500 hover:bg-red-600 text-black rounded-lg px-4 py-2 text-sm font-semibold">Delete</button>
                    </td>
                </tr>
                <tr class="bg-white-100 border-slate-200 border-t-4">
                    <td class="px-6 py-4">5</td>
                    <td class="px-6 py-4">55555</td>
                    <td class="px-6 py-4">5/20/2026</td>
                    <td class="px-6 py-4">10:00 AM</td>
                    <td class="px-6 py-4">5:00 PM</td>
                    <td class="px-6 py-4">7</td>
                    <td class="px-6 py-4">
                        <button class="bg-green-500 rounded-full px-4 py-1">Present</button>
                    </td>
                    <td class="px-6 py-4 space-x-4 inline-flex">

                        <Link href="/edit"
                            class="bg-yellow-400 hover:bg-yellow-500 rounded-lg px-4 py-2 text-sm font-semibold">Edit
                        </Link>

                        <button
                            class="bg-red-500 hover:bg-red-600 text-black rounded-lg text-sm font-semibold px-4 py-1">Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>