<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PaginationLinks from '../../Components/PaginationLinks.vue';

const props = defineProps({
    salaries: {
        type: Object,
        required: true
    }
});

const searchQuery = ref('');

const filteredSalaries = computed(() => {
    const offset = (props.salaries.current_page - 1) * props.salaries.per_page;
    
    let result = props.salaries.data.map((s, index) => ({
        ...s,
        serialNo: offset + index + 1
    }));

    if (searchQuery.value) {
        result = result.filter(s =>
            s.serialNo.toString().includes(searchQuery.value) ||
            s.employee_id.toString().includes(searchQuery.value) ||
            s.employee_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            s.role.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            s.department.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }

    return result;
});

const formatCurrency = (value) => {
    return '₱' + Number(value).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};
</script>

<template>
    <div class="flex flex-col px-6">

        <Head title=" | Payroll Management" />

        <h1 class="text-center text-4xl font-bold mb-12">Payroll Management</h1>

        <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
            <input type="search" v-model="searchQuery" placeholder="Search by Serial No"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />

            <div class="flex flex-wrap justify-end gap-3">
                <a href="/hr/reports/employees" target="_blank"
                    class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-md">
                    Employee List PDF
                </a>

                <a href="/hr/reports/payroll" target="_blank"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md">
                    Salary Report
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full text-left whitespace-nowrap">
                <thead class="bg-gray-400 text-black font-medium">
                    <tr>
                        <th>Serial No</th>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th class="text-center">Role</th>
                        <th>Department</th>
                        <th>Basic Salary</th>
                        <th>Net Salary</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(salary, index) in filteredSalaries" :key="salary.data"
                        class="border-t-4 border-gray-200">
                        <td>{{ salary.serialNo }}</td>
                        <td>{{ salary.employee_id }}</td>
                        <td>{{ salary.employee_name }}</td>
                        <td class="text-center">
                            <span :class="{
                                'bg-red-100 text-red-700': salary.role === 'admin',
                                'bg-blue-100 text-blue-700': salary.role === 'hr',
                                'bg-green-100 text-green-700': salary.role === 'employee'
                            }"
                                class="inline-block w-24 text-center py-2 rounded-full text-sm font-semibold transition capitalize">
                                {{ salary.role }}
                            </span>
                        </td>
                        <td>{{ salary.department }}</td>
                        <td>{{ formatCurrency(salary.full_salary) }}</td>
                        <td>{{ formatCurrency(salary.net_salary) }}</td>
                        <td class="text-center">
                            <Link :href="`/hr/salaries/${salary.id}`"
                                class="bg-blue-500 hover:bg-blue-600 inline-flex items-center justify-center w-24 py-2 rounded-md text-sm font-semibold transition">
                                View
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="filteredSalaries.length === 0">
                        <td colspan="8" class="p-8 text-center text-gray-500 border-t-4 border-slate-200">
                            No salary records found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <PaginationLinks :paginator="salaries" />
        </div>
    </div>
</template>