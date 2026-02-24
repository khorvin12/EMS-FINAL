<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    employee: {
        type: Object,
        required: true
    },
    salary: {
        type: Object,
        required: true
    },
    attendance: {
        type: Object,
        default: () => ({
            absences: 0,
            late_count: 0,
            total_late_minutes: 0,
            total_hours_worked: 0,
            expected_hours: 176,
            undertime_hours: 0,
            overtime_hours: 0,
            overtime_pay: 0,
            present_days: 0,
            total_working_days: 22
        })
    },
    payrollGenerated: {
        type: Boolean,
        default: false
    }
});

const isGenerating = ref(false);

const calculatedSalary = computed(() => {
    const grossSalary        = parseFloat(props.salary.full_salary || 0);
    const workingDaysPerMonth = 22;
    const workingHoursPerDay  = 8;
    const dailyRate           = grossSalary / workingDaysPerMonth;
    const hourlyRate          = dailyRate / workingHoursPerDay;
    const overtimeRate        = hourlyRate * 1.25;

    if (!props.payrollGenerated) {
        return {
            gross: grossSalary,
            dailyRate,
            hourlyRate,
            absenceDeduction:   0,
            lateDeduction:      0,
            undertimeDeduction: 0,
            overtimePay:        0,
            totalDeductions:    0,
            net:                grossSalary
        };
    }

    const absenceDeduction   = dailyRate  * (props.attendance.absences || 0);
    const lateDeduction      = hourlyRate * ((props.attendance.total_late_minutes || 0) / 60);
    const undertimeDeduction = hourlyRate * (props.attendance.undertime_hours || 0);
    const overtimePay        = overtimeRate * (props.attendance.overtime_hours || 0);
    const totalDeductions    = absenceDeduction + lateDeduction + undertimeDeduction;
    const netSalary          = grossSalary - totalDeductions + overtimePay;

    return {
        gross: grossSalary,
        dailyRate,
        hourlyRate,
        absenceDeduction,
        lateDeduction,
        undertimeDeduction,
        overtimePay,
        totalDeductions,
        net: netSalary
    };
});

const formatCurrency = (value) => {
    return Number(value).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};

const generatePayroll = () => {
    isGenerating.value = true;
router.post(`/hr/salaries/${props.employee.id}/generate-payroll`, {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => isGenerating.value = false,
        onError: () => {
            isGenerating.value = false;
            alert('Failed to generate payroll. Please try again.');
        }
    });
};

const goBack = () => router.visit('/hr/salaries');
</script>

<template>
    <div class="max-w-5xl mx-auto py-8 px-4">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white">Employee Salary Details</h2>
                <button @click="goBack" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                    ← Back to List
                </button>
            </div>

            <div class="p-8">
                <!-- Employee Info -->
                <div class="bg-gray-50 rounded-xl p-6 mb-8 grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Employee ID</p>
                      <p class="text-lg font-bold text-gray-900">{{ employee.id }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Employee Name</p>
                        <p class="text-lg font-bold text-gray-900">{{ employee.name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Department</p>
                        <p class="text-lg font-bold text-gray-900">{{ employee.department }}</p>
                    </div>
                </div>

                <!-- Rates -->
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-lg p-6 border-l-4 border-purple-500 shadow-sm">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Daily Rate</p>
                        <p class="text-3xl font-bold text-gray-900 mb-1">₱{{ formatCurrency(calculatedSalary.dailyRate) }}</p>
                        <p class="text-sm text-gray-500">Gross Salary ÷ 22 days</p>
                    </div>
                    <div class="bg-white rounded-lg p-6 border-l-4 border-indigo-500 shadow-sm">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Hourly Rate</p>
                        <p class="text-3xl font-bold text-gray-900 mb-1">₱{{ formatCurrency(calculatedSalary.hourlyRate) }}</p>
                        <p class="text-sm text-gray-500">Daily Rate ÷ 8 hours</p>
                    </div>
                </div>

                <!-- Generate Button -->
                <div v-if="!payrollGenerated" class="mb-8 text-center">
                    <button @click="generatePayroll" :disabled="isGenerating"
                        class="bg-green-500 hover:bg-green-600 disabled:bg-gray-400 text-white font-bold px-8 py-4 rounded-lg text-lg shadow-lg transition-all transform hover:scale-105 disabled:transform-none">
                        <span v-if="!isGenerating">🧮 Generate Payroll</span>
                        <span v-else>Generating...</span>
                    </button>
                    <p class="text-sm text-gray-600 mt-3">Calculate deductions based on latest attendance data</p>
                </div>

                <!-- Attendance Summary -->
                <div v-if="payrollGenerated" class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8 rounded-lg">
                    <h3 class="font-bold text-lg mb-4 text-gray-800">📊 Attendance Summary (Current Period)</h3>

                    <!-- Hours worked -->
                    <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Total Hours Worked</p>
                                <p class="text-2xl font-bold text-orange-600">
                                    {{ attendance.total_hours_worked }} / {{ attendance.expected_hours }} hours
                                </p>
                                <p class="text-xs text-gray-500 mt-1">({{ attendance.present_days }} of {{ attendance.total_working_days }} working days completed)</p>
                            </div>
                            <div v-if="attendance.undertime_hours > 0" class="text-right">
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Undertime</p>
                                <p class="text-xl font-bold text-red-600">{{ attendance.undertime_hours }} hours</p>
                                <p class="text-xs text-gray-500 mt-1">Deduction: ₱{{ formatCurrency(calculatedSalary.undertimeDeduction) }}</p>
                            </div>
                            <div v-else-if="attendance.total_hours_worked > 0" class="text-right">
                                <p class="text-sm text-green-600 font-semibold">✓ Full Hours Completed</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stats grid -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Absences</p>
                            <p class="text-2xl font-bold text-red-600">{{ attendance.absences }} day(s)</p>
                            <p class="text-xs text-gray-500 mt-1">Deduction: ₱{{ formatCurrency(calculatedSalary.absenceDeduction) }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Late Arrivals</p>
                            <p class="text-2xl font-bold text-orange-600">{{ attendance.late_count }} time(s)</p>
                            <p class="text-xs text-gray-500 mt-1">Total: {{ attendance.total_late_minutes }} mins</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Late Deduction</p>
                            <p class="text-2xl font-bold text-orange-600">₱{{ formatCurrency(calculatedSalary.lateDeduction) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Based on hourly rate</p>
                        </div>
                        <!-- Overtime box -->
                        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-400">
                            <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Overtime</p>
                            <p class="text-2xl font-bold text-green-600">{{ attendance.overtime_hours || 0 }} hrs</p>
                            <p class="text-xs text-gray-500 mt-1">+₱{{ formatCurrency(calculatedSalary.overtimePay) }} (1.25x)</p>
                        </div>
                    </div>
                </div>

                <!-- Salary Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow-sm p-8 border-l-4 border-green-500 hover:shadow-md transition-all">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 opacity-70">Gross Salary</p>
                        <p class="text-5xl font-extrabold text-gray-900 mb-2 leading-none">₱{{ formatCurrency(calculatedSalary.gross) }}</p>
                        <p class="text-sm text-gray-500 font-medium">Monthly base</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-8 border-l-4 border-red-500 hover:shadow-md transition-all">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 opacity-70">Total Deductions</p>
                        <p class="text-5xl font-extrabold text-gray-900 mb-2 leading-none">₱{{ formatCurrency(calculatedSalary.totalDeductions) }}</p>
                        <div class="text-xs space-y-1">
                            <p v-if="!payrollGenerated" class="text-gray-400 italic">Pending generation...</p>
                            <template v-else>
                                <p v-if="attendance.absences > 0" class="text-red-600">Absence: -₱{{ formatCurrency(calculatedSalary.absenceDeduction) }}</p>
                                <p v-if="attendance.late_count > 0" class="text-orange-600">Late: -₱{{ formatCurrency(calculatedSalary.lateDeduction) }}</p>
                                <p v-if="attendance.undertime_hours > 0" class="text-red-600">Undertime: -₱{{ formatCurrency(calculatedSalary.undertimeDeduction) }}</p>
                                <p v-if="attendance.overtime_hours > 0" class="text-green-600">Overtime: +₱{{ formatCurrency(calculatedSalary.overtimePay) }}</p>
                                <p v-if="calculatedSalary.totalDeductions === 0" class="text-green-600 font-bold">Perfect Attendance! ✓</p>
                            </template>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-8 text-white hover:shadow-xl transition-all">
                        <p class="text-sm font-semibold uppercase tracking-wide mb-3 opacity-90">Net Salary</p>
                        <p class="text-5xl font-extrabold mb-2 leading-none">₱{{ formatCurrency(calculatedSalary.net) }}</p>
                        <p class="text-sm opacity-90 font-medium">Estimated take-home pay</p>
                    </div>
                </div>

                <!-- Regenerate -->
                <div v-if="payrollGenerated" class="mt-8 flex justify-center">
                    <button @click="generatePayroll" :disabled="isGenerating"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-6 py-3 rounded-lg transition-colors flex items-center gap-2">
                        <span>{{ isGenerating ? 'Updating...' : '🔄 Regenerate Payroll' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>