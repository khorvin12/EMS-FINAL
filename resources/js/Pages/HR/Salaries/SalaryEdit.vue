<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <h1 class="text-center text-4xl font-bold mb-12">Salary Details</h1>

    <div class="flex justify-between mb-6">
        <input type="search" placeholder="Search By Employee ID" class="bg-white p-2 border border-gray-300 rounded-md
            focus:outline-none focus:border-blue-500" />

        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
            Save All Changes
        </button>
    </div>

    <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
        <thead>
            <tr class="bg-gray-400 text-black font-medium text-lg text-left">
                <th class="p-6">Serial No</th>
                <th class="p-6">Employee ID</th>
                <th class="p-6">Month</th>
                <th class="p-6">Basic Salary</th>
                <th class="p-6">Deductions</th>
            </tr>
        </thead>

        <tbody>
            <tr class="border-t-4 border-gray-200">
                <td class="p-6">1</td>
                <td class="p-6">11111</td>

                <td class="p-6">
                    <input type="date" name="payment_date" class="bg-slate-100 border border-slate-200 rounded-md p-1" />
                </td>

                <td class="p-6">
                    <label class="mr-2">$</label>
                    <input type="number" name="basic_salary" step="0.01" min="0" placeholder="0.00"
                        class="border border-slate-200 rounded-md p-1 focus:outline-none">
                </td>

                <td class="p-6">
                    <label class="mr-2">$</label>
                    <input type="number" name="deductions" step="0.01" min="0" placeholder="0.00"
                        class="border border-slate-200 rounded-md p-1 focus:outline-none">
                </td>
            </tr>
        </tbody>
    </table>
</template>