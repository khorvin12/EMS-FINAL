<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import HRLayout from '@/Layouts/HRLayout.vue';

const props = defineProps({
    salaries: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');

const filteredSalaries = computed(() => {
    if (!searchQuery.value) {
        return props.salaries;
    }
    return props.salaries.filter(salary => 
        salary.employee_id.toString().includes(searchQuery.value) ||
        salary.employee_name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const formatCurrency = (value) => {
    return '₱' + Number(value).toLocaleString('en-PH', { 
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};
</script>

<template>

        <h1 class="text-center text-4xl font-bold mb-12">Salary Details</h1>
        
        <div class="flex justify-between mb-6">
            <input 
                type="search" 
                v-model="searchQuery"
                placeholder="Search By Employee ID or Name" 
                class="bg-white p-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-400" 
            />
            <Link 
                href="/hr/salary-report" 
                class="bg-green-500 text-black font-medium px-4 py-2 rounded-md hover:bg-green-600"
            >
                Salary Report
            </Link>
        </div>

        <table class="w-full shadow-lg overflow-hidden table-fixed bg-white rounded-lg">
            <thead>
                <tr class="bg-gray-400 text-black font-medium text-lg text-left">
                    <th class="p-6">Serial No</th>
                    <th class="p-6">Employee ID</th>
                    <th class="p-6">Employee Name</th>
                    <th class="p-6">Department</th>
                    <th class="p-6">Basic Salary</th>
                    <th class="p-6">Net Salary</th>
                    <th class="p-6">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    v-for="(salary, index) in filteredSalaries" 
                    :key="salary.id"
                    class="border-t-4 border-gray-200 hover:bg-gray-50"
                >
                    <td class="p-6">{{ index + 1 }}</td>
                    <td class="p-6">{{ salary.employee_id }}</td>
                    <td class="p-6">{{ salary.employee_name }}</td>
                    <td class="p-6">{{ salary.department }}</td>
                    <td class="p-6">{{ formatCurrency(salary.full_salary) }}</td>
                    <td class="p-6">{{ formatCurrency(salary.net_salary) }}</td>
                    <td class="p-6">
                        <Link 
                            :href="`/hr/salaries/${salary.employee_id}`"
                            class="bg-blue-500 hover:bg-blue-600 rounded-sm px-6 py-2 font-medium text-white inline-block"
                        >
                            View
                        </Link>
                    </td>
                </tr>
                <tr v-if="filteredSalaries.length === 0">
                    <td colspan="7" class="p-6 text-center text-gray-500">
                        No salary records found
                    </td>
                </tr>
            </tbody>
        </table>
</template>