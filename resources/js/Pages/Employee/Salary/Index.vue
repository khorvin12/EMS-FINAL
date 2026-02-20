<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    employee: {
        type: Object,
        required: false,
        default: () => ({
            employee_id: 'Loading...',
            name: 'Loading...',
            department: 'Loading...',
        })
    },
    salary: {
        type: Object,
        required: false,
        default: () => ({
            full_salary: 0,
            deductions: 0
        })
    },
    absences: {
        type: Number,
        default: 0
    }
});

const calculatedSalary = computed(() => {
    const grossSalary = parseFloat(props.salary.full_salary || 0);
    const baseDeductions = parseFloat(props.salary.deductions || 0);
    
    const workingDaysPerMonth = 22;
    const dailyRate = grossSalary / workingDaysPerMonth;
    const absenceDeduction = dailyRate * props.absences;
    
    const totalDeductions = baseDeductions + absenceDeduction;
    const netSalary = grossSalary - totalDeductions;
    
    return {
        gross: grossSalary,
        deductions: totalDeductions,
        net: netSalary,
        absenceDeduction: absenceDeduction
    };
});

const formatCurrency = (value) => {
    return Number(value).toLocaleString('en-PH', { 
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
};
</script>

<template>

    <Head title="Your Monthly Salary" />

    <!-- Just the content, no layout wrapper -->
    <div class="max-w-7xl mx-auto">

        <div class="overflow-hidden shadow-sm sm:rounded-lg">
            
            <div class="p-8 bg-gray-50">

                <h2 class="text-3xl font-bold text-gray-900 mb-8">Your Monthly Salary</h2>

                <!-- Employee Info - Now with 3 columns instead of 4 -->
                <div class="bg-gray-200 rounded-lg p-7 mb-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Employee ID</p>
                        <p class="text-base font-semibold text-gray-900">{{ employee.employee_id }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Employee Name</p>
                        <p class="text-base font-semibold text-gray-900">{{ employee.name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Department</p>
                        <p class="text-base font-semibold text-gray-900">{{ employee.department }}</p>
                    </div>
                </div>

                <!-- Salary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-7xl">

                    <!-- Gross Salary Card -->
                    <div class="bg-white rounded-xl shadow-sm p-8 border-l-4 border-green-500 hover:shadow-md transition-all hover:-translate-y-1 duration-300">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 opacity-70">Gross Salary</p>
                        <p class="text-5xl font-extrabold text-gray-900 mb-2 leading-none">₱{{ formatCurrency(calculatedSalary.gross) }}</p>
                        <p class="text-sm text-gray-500 opacity-70 font-medium">Base salary + allowances</p>
                    </div>

                    <!-- Total Deductions Card -->
                    <div class="bg-white rounded-xl shadow-sm p-8 border-l-4 border-red-500 hover:shadow-md transition-all hover:-translate-y-1 duration-300">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 opacity-70">Total Deductions</p>
                        <p class="text-5xl font-extrabold text-gray-900 mb-2 leading-none">₱{{ formatCurrency(calculatedSalary.deductions) }}</p>
                        <p class="text-sm text-gray-500 opacity-70 font-medium">
                            Taxes + contributions
                            <span v-if="absences > 0" class="block text-xs text-red-600 mt-1">
                                (includes {{ absences }} day{{ absences > 1 ? 's' : '' }} absence deduction: ₱{{ formatCurrency(calculatedSalary.absenceDeduction) }})
                            </span>
                        </p>
                    </div>

                    <!-- Net Salary Card (Full Width) -->
                    <div class="md:col-span-2 bg-gradient-to-r w-149 from-blue-500 to-blue-600 rounded-xl shadow-lg p-8 text-white hover:shadow-xl transition-all hover:-translate-y-1 duration-300">
                        <p class="text-sm font-semibold uppercase tracking-wide mb-3 opacity-90">Net Salary</p>
                        <p class="text-5xl font-extrabold mb-2 leading-none">₱{{ formatCurrency(calculatedSalary.net) }}</p>
                        <p class="text-sm opacity-90 font-medium">Take-home pay this month</p>
                    </div>

                </div>

            </div>

        </div>

    </div>
</template>