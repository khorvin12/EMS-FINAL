<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>

    <!-- Content Title -->
    <h1 class="text-center text-3xl font-bold mb-12">Manage Leave Request</h1>

    <div class="flex items-center justify-between mb-6">
        <!-- Search -->
        <input type="search" placeholder="Search By Serial No." class="bg-white rounded-md p-2" />

        <!-- Date Filter -->
        <div class="flex items-center gap-2">
            <label class="text-sm font-medium">Filter by Date</label>
            <input type="date" name="date" class="bg-white rounded-md p-2">
        </div>
    </div>


    <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
        <thead>
            <tr class="bg-gray-400 text-black font-medium text-lg">
                <th class="p-6">Serial No.</th>
                <th class="p-6">Employee ID</th>
                <th class="p-6">Name</th>
                <th class="p-6">Start Date</th>
                <th class="p-6">End Date</th>
                <th class="p-6">Type</th>
                <th class="p-6">Status</th>
                <th class="p-6">Action</th>
            </tr>
        </thead>

        <tbody>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">1</td>
                <td class="p-4">11111</td>
                <td class="p-4">Kier</td>
                <td class="p-4">
                    February 1, 2026
                </td>
                <td class="p-4">
                    February 3, 2026
                </td>
                <td class="p-4">Sick</td>
                <td class="p-4">N/A</td>
                <td class="p-4 space-x-4 inline-flex">
                    <Link href="/edit" class="bg-blue-500 hover:bg-blue-400 rounded-sm px-4 py-1">View</Link>
                </td>
            </tr>

            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">2</td>
                <td class="p-4">22222</td>
                <td class="p-4">Lucky Jade</td>
                <td class="p-4">
                    February 14, 2026
                </td>
                <td class="p-4">
                    February 15, 2026
                </td>
                <td class="p-4">Emergency</td>
                <td class="p-4">N/A</td>
                <td class="p-4 space-x-4 inline-flex">
                    <Link href="/edit" class="bg-blue-500 hover:bg-blue-400 rounded-sm px-4 py-1">View</Link>
                </td>
            </tr>

            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">3</td>
                <td class="p-4">33333</td>
                <td class="p-4">Mark</td>
                <td class="p-4">
                    February 20, 2026
                </td>
                <td class="p-4">
                    February 27, 2026
                </td>
                <td class="p-4">Bereavement</td>
                <td class="p-4">N/A</td>
                <td class="p-4 space-x-4 inline-flex">
                    <Link href="/edit" class="bg-blue-500 hover:bg-blue-400 rounded-sm px-4 py-1">View</Link>
                </td>
            </tr>

            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">4</td>
                <td class="p-4">44444</td>
                <td class="p-4">Khorvin</td>
                <td class="p-4">
                    March 1, 2026
                </td>
                <td class="p-4">
                    March 10, 2026
                </td>
                <td class="p-4">Paternity</td>
                <td class="p-4">N/A</td>
                <td class="p-4 space-x-4 inline-flex">
                    <Link href="/edit" class="bg-blue-500 hover:bg-blue-400 rounded-sm px-4 py-1">View</Link>
                </td>
            </tr>

            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">5</td>
                <td class="p-4">55555</td>
                <td class="p-4">Nico</td>
                <td class="p-4">
                    March 15, 2026
                </td>
                <td class="p-4">
                    March 15, 2056
                </td>
                <td class="p-4">Vacation</td>
                <td class="p-4">N/A</td>
                <td class="p-4 space-x-4 inline-flex">
                    <Link href="/edit" class="bg-blue-500 hover:bg-blue-400 rounded-sm px-4 py-1">View</Link>
                </td>
            </tr>

            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">6</td>
                <td class="p-4">66666</td>
                <td class="p-4">Joshua</td>
                <td class="p-4">
                    March 15, 2026
                </td>
                <td class="p-4">
                    March 20, 2026
                </td>
                <td class="p-4">Unpaid</td>
                <td class="p-4">N/A</td>
                <td class="p-4 space-x-4 inline-flex">
                    <Link href="/edit" class="bg-blue-500 hover:bg-blue-400 rounded-sm px-4 py-1">View</Link>
                </td>
            </tr>

            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">7</td>
                <td class="p-4">77777</td>
                <td class="p-4">Paloma</td>
                <td class="p-4">
                    March 1, 2026
                </td>
                <td class="p-4">
                    June 1, 2026
                </td>
                <td class="p-4">Maternity</td>
                <td class="p-4">N/A</td>
                <td class="p-4 space-x-4 inline-flex">
                    <Link href="/edit" class="bg-blue-500 hover:bg-blue-400 rounded-sm px-4 py-1">View</Link>
                </td>
            </tr>
        </tbody>
    </table>
</template>