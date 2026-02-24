<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <h1 class="text-center text-3xl font-bold mb-12">Your Attendance</h1>

        <div class="flex justify-between mb-6">
            <input type="search" placeholder="Search By Name or ID"
                class="bg-white rounded-md p-2 border border-gray-300 focus:outline-none focus:border-blue-400" />

            <Link href="/attendance/create" class="bg-green-500 text-black font-semibold px-4 py-2 rounded-md hover:bg-green-600">Add
                Attendance</Link>
        </div>


        <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg text-left">
            <thead>
                <tr class="bg-gray-400 text-black font-medium">
                    <th>Serial No</th>
                    <th>Employee ID</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Hours</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <tr class="bg-white-100 border-slate-200 border-t-4">
                    <td>1</td>
                    <td>11111</td>
                    <td>1/30/2026</td>
                    <td>8:00 AM</td>
                    <td>5:00 PM</td>
                    <td>9</td>
                    <td>
                        <button class="bg-green-500 rounded-full px-4 py-1">Present</button>
                    </td>
                    <td class="gap-2 inline-flex">

                        <Link href="/edit"
                            class="bg-yellow-400 hover:bg-yellow-500 rounded-lg px-4 py-2 text-sm font-semibold">Edit
                        </Link>

                        <button
                            class="bg-red-500 hover:bg-red-600 text-black rounded-lg px-4 py-2 text-sm font-semibold">Delete</button>
                    </td>
                </tr>
                <tr class="bg-white-100 border-slate-200 border-t-4">
                    <td>2</td>
                    <td>22222</td>
                    <td>2/20/2026</td>
                    <td>9:00 AM</td>
                    <td>5:00 PM</td>
                    <td>8</td>
                    <td>
                        <button class="bg-yellow-400 rounded-full px-4 py-1">Late</button>
                    </td>
                    <td class="gap-2 inline-flex">

                        <Link href="/edit"
                            class="bg-yellow-400 hover:bg-yellow-500 rounded-lg px-4 py-2 text-sm font-semibold">Edit
                        </Link>

                        <button
                            class="bg-red-500 hover:bg-red-600 text-black rounded-lg px-4 py-2 text-sm font-semibold">Delete</button>
                    </td>
                </tr>
                <tr class="bg-white-100 border-slate-200 border-t-4">
                    <td>3</td>
                    <td>33333</td>
                    <td>3/10/2026</td>
                    <td>--</td>
                    <td>--</td>
                    <td>0</td>
                    <td>
                        <button class="bg-red-500 rounded-full px-4 py-1">Absent</button>
                    </td>
                    <td class="gap-2 inline-flex">
                        <Link href="/edit"
                            class="bg-yellow-400 hover:bg-yellow-500 rounded-lg px-4 py-2 text-sm font-semibold">Edit
                        </Link>

                        <button
                            class="bg-red-500 hover:bg-red-600 text-black rounded-lg px-4 py-2 text-sm font-semibold">Delete</button>
                    </td>
                </tr>
                <tr class="bg-white-100 border-slate-200 border-t-4">
                    <td>4</td>
                    <td>44444</td>
                    <td>4/20/2026</td>
                    <td>--</td>
                    <td>--</td>
                    <td>0</td>
                    <td>
                        <button class="bg-blue-400 rounded-full px-4 py-1">On Leave</button>
                    </td>
                    <td class="gap-2 inline-flex">

                        <Link href="/edit"
                            class="bg-yellow-400 hover:bg-yellow-500 rounded-lg px-4 py-2 text-sm font-semibold">Edit
                        </Link>
                        <button
                            class="bg-red-500 hover:bg-red-600 text-black rounded-lg px-4 py-2 text-sm font-semibold">Delete</button>
                    </td>
                </tr>
                <tr class="bg-white-100 border-slate-200 border-t-4">
                    <td>5</td>
                    <td>55555</td>
                    <td>5/20/2026</td>
                    <td>10:00 AM</td>
                    <td>5:00 PM</td>
                    <td>7</td>
                    <td>
                        <button class="bg-green-500 rounded-full px-4 py-1">Present</button>
                    </td>
                    <td class="gap-2 inline-flex">

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