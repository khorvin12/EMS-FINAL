<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <h1 class="text-center text-4xl font-bold mb-12">Salary Details</h1>

    <div class="flex justify-between mb-6">
        <input type="search" placeholder="Search By Name or ID" class="bg-white p-2 border border-gray-300 rounded-md
           focus:outline-none focus:border-blue-400" />

        <Link href="/addsalary" class="bg-green-500 text-black font-semibold px-4 py-2 rounded-md hover:bg-green-600">
            Salary Report
        </Link>
    </div>

    <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">

        <thead>
            <tr class="bg-gray-400 text-black font-medium text-left">
                <th>Serial No</th>
                <th>Employee ID</th>
                <th>Month</th>
                <th>Basic Salary</th>
                <th>Deductions</th>
                <th>Net Salary</th>
            </tr>
        </thead>

        <tbody>
            <tr class="border-t-4 border-gray-200">
                <td>1</td>
                <td>11111</td>

                <td>
                    February 1, 2026
                </td>

                <td>$12,000,000</td>
                <td>$15,000,000</td>

                <td class="flex items-center justify-between">

                    <span>$1,000,000</span>

                    <Link href="/editdepartment"
                        class="bg-yellow-400 hover:bg-yellow-500 rounded-lg px-4 py-2 text-sm font-semibold text-black">
                        Edit
                    </Link>

                </td>
            </tr>
        </tbody>

    </table>

</template>