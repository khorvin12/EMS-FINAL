<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    salaries: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');
const showEmployee = ref(true);
const showHR = ref(false);
const showAdmin = ref(false);

const filteredSalaries = computed(() => {
    let result = props.salaries;

    result = result.filter(s => {
        if (s.role === 'employee' && !showEmployee.value) return false;
        if (s.role === 'hr' && !showHR.value) return false;
        if (s.role === 'admin' && !showAdmin.value) return false;
        return true;
    });

    if (searchQuery.value) {
        result = result.filter(s =>
            s.employee_id.toString().includes(searchQuery.value) ||
            s.employee_name.toLowerCase().includes(searchQuery.value.toLowerCase())
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
        <h1 class="text-center text-4xl font-bold mb-12">Salary Details</h1>

        <div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
            <input type="search" v-model="searchQuery" placeholder="Search By Employee ID or Name"
                class="border border-gray-300 rounded-lg px-4 py-2 w-80 focus:outline-none focus:ring-2 focus:ring-blue-400" />

            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" v-model="showEmployee" class="w-4 h-4 accent-green-500" />
                    <span class="text-sm font-medium text-gray-700">Show Employee</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" v-model="showHR" class="w-4 h-4 accent-blue-500" />
                    <span class="text-sm font-medium text-gray-700">Show HR</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" v-model="showAdmin" class="w-4 h-4 accent-red-500" />
                    <span class="text-sm font-medium text-gray-700">Show Admin</span>
                </label>
            </div>

            <Link href="/hr/salary-report" class="bg-green-500 hover:bg-green-600 font-semibold px-4 py-2 rounded-md">
                Salary Report
            </Link>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full text-left">
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
                    <tr v-for="(salary, index) in filteredSalaries" :key="salary.id" class="border-t-4 border-gray-200">
                        <td>{{ index + 1 }}</td>
                        <td>{{ salary.employee_id }}</td>
                        <td>{{ salary.employee_name }}</td>
                        <td class="text-center">
                            <span :class="{
                                'bg-red-100 text-red-700': salary.role === 'admin',
                                'bg-blue-100 text-blue-700': salary.role === 'hr',
                                'bg-green-100 text-green-700': salary.role === 'employee'
                            }" class="inline-block w-24 text-center py-2 rounded-full text-sm font-semibold transition capitalize">
                                {{ salary.role }}
                            </span>
                        </td>
                        <td>{{ salary.department }}</td>
                        <td>{{ formatCurrency(salary.full_salary) }}</td>
                        <td>{{ formatCurrency(salary.net_salary) }}</td>
                        <td class="text-center">
                            <Link :href="`/hr/salaries/${salary.id}`"
                                class="bg-blue-500 hover:bg-blue-600 w-20 py-2 rounded-md text-sm font-semibold inline-block">
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
    </div>
</template>