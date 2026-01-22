<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <h1 class="text-center text-3xl font-bold mb-12">Your Salary Details</h1>

    <div class="flex justify-between mb-6">
        <div class="bg-white rounded-md p-2">
            <input type="search" placeholder="Search By SNO" />
        </div>
    </div>

    <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
        <thead class="border-slate-200 text-slate-600">
            <tr class="bg-gray-400 text-black font-medium">
                <th class="p-6">SNO</th>
                <th class="">Employee ID</th>
                <th class="">Month</th>
                <th class="">Full Salary</th>
                <th class="">Deductions</th>
                <th class="">Salary</th>
                <th class="">Status</th>
            </tr>
        </thead>

        <tbody>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">1</td>
                <td class="p-4">11111</td>
                <td class="p-4">January 2026</td>
                <td class="p-4">50,000</td>
                <td class="p-4">20,000</td>
                <td class="p-4">30,000</td>
                <td class="p-4">Paid</td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">2</td>
                <td class="p-4">22222</td>
                <td class="p-4">February 2026</td>
                <td class="p-4">50,000</td>
                <td class="p-4">0</td>
                <td class="p-4">50,000</td>
                <td class="p-4">Paid</td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">3</td>
                <td class="p-4">33333</td>
                <td class="p-4">March 2026</td>
                <td class="p-4">50,000</td>
                <td class="p-4">5,000</td>
                <td class="p-4">45,000</td>
                <td class="p-4">Paid</td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">4</td>
                <td class="p-4">44444</td>
                <td class="p-4">April 2026</td>
                <td class="p-4">50,000</td>
                <td class="p-4">10,000</td>
                <td class="p-4">40,000</td>
                <td class="p-4">Paid</td>
            </tr>
            <tr class="bg-white-100 text-center border-slate-200 border-t-4">
                <td class="p-4">5</td>
                <td class="p-4">55555</td>
                <td class="p-4">May 2026</td>
                <td class="p-4">50,000</td>
                <td class="p-4">15,000</td>
                <td class="p-4">35,000</td>
                <td class="p-4">Paid</td>
            </tr>
        </tbody>
    </table>
</template>