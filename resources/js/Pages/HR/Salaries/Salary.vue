<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <h1 class="text-center text-4xl font-bold mb-12">Salary Details</h1>

    <div class="flex justify-between mb-6">
        <input type="search" placeholder="Search By Employee ID" class="bg-white p-2 border border-gray-300 rounded-md
           focus:outline-none focus:border-blue-400" />

        <Link href="/addsalary" class="bg-green-500 text-black font-medium px-4 py-2 rounded-md hover:bg-green-600">
            Salary Report
        </Link>
    </div>

    <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
        <thead>
            <tr class="bg-gray-400 text-black font-medium text-lg text-left">
                <th class="p-6">Serial No</th>
                <th class="p-6">Employee ID</th>
                <th class="p-6">Month</th>
                <th class="p-6">Basic Salary</th>
                <th class="p-6">Deductions</th>
                <th class="p-6">Net Salary</th>
            </tr>
        </thead>

        <tbody>
            <tr class="border-t-4 border-gray-200">
                <td class="p-6">1</td>
                <td class="p-6">11111</td>

                <td class="p-6">
                    February 1, 2026
                </td>

                <td class="p-6">$12,000,000</td>
                <td class="p-6">$15,000,000</td>

                <td class="p-6">
                    <div class="flex items-center justify-between">
                        <span>$1,000,000</span>
                        <Link href="/editdepartment"
                            class="bg-yellow-400 hover:bg-yellow-500 rounded-sm px-4 py-1 font-medium text-black">
                            Edit
                        </Link>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

</template>