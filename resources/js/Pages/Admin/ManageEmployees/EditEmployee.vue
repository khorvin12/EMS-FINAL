<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    employee: { type: Object, required: true },
    departments: { type: Array, default: () => [] },
});

const showSuccess = ref(false);

const formatDate = (dateString) => {
    if (!dateString) return ''
    return new Date(dateString).toISOString().split('T')[0]
}

const form = useForm({
    first_name: props.employee?.first_name || "",
    last_name: props.employee?.last_name || "",
    email: props.employee?.email || "",
    phone: props.employee?.phone || "",
    dob: formatDate(props.employee?.dob),
    gender: props.employee?.gender || "",
    civil_status: props.employee?.civil_status || "",
    department_id: props.employee?.department_id || "",
    hire_date: formatDate(props.employee?.hire_date),
    salary: props.employee?.salary || "",
    role: props.employee?.role || "",
});

const formFields = [
    { name: "first_name", label: "First Name", type: "text", placeholder: "Enter First Name" },
    { name: "last_name", label: "Last Name", type: "text", placeholder: "Enter Last Name" },
    { name: "email", label: "Email", type: "email", placeholder: "Enter Email" },
    { name: "phone", label: "Phone", type: "text", placeholder: "Enter Phone" },
    { name: "dob", label: "Date of Birth", type: "date" },
    { name: "gender", label: "Gender", type: "select", options: ["Male", "Female"], placeholder: "Select Gender" },
    { name: "civil_status", label: "Civil Status", type: "select", options: ["Single", "Married"], placeholder: "Select Status" },
    { name: "department_id", label: "Department", type: "select", options: computed(() => props.departments), placeholder: "Select Department" },
    { name: "hire_date", label: "Hire Date", type: "date" },
    { name: "salary", label: "Salary", type: "number", placeholder: "Salary", step: "0.01" },
    { name: "role", label: "Role", type: "select", options: [{ value: "admin", label: "Admin" }, { value: "hr", label: "HR" }, { value: "employee", label: "Employee" }], placeholder: "Select Role" },
];

function submit() {
    form.put(`/admin/edit/${props.employee.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess.value = true
            setTimeout(() => {
                showSuccess.value = false
            }, 2000)
        },
    });
}
</script>

<template>
    <!-- Success Notification -->
    <Transition name="fade">
        <div v-if="showSuccess" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="font-semibold">Employee updated successfully!</span>
            </div>
        </div>
    </Transition>

    <div class="bg-white border-4 border-yellow-400 rounded-lg p-8 max-w-3xl mx-auto overflow-x-auto">

        <Head title=" | Edit Employee" />

        <h1 class="text-2xl font-bold mb-6">Edit Employee</h1>

        <!-- Error Display -->
        <div v-if="form.errors && Object.keys(form.errors).length"
            class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-600 font-semibold mb-2">
                Please fix the following errors:
            </p>
            <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                <li v-for="(error, field) in form.errors" :key="field">
                    {{ error }}
                </li>
            </ul>
        </div>

        <form @submit.prevent="submit" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Dynamic Form Fields -->
            <div v-for="field in formFields" :key="field.name">
                <label class="text-sm font-semibold">{{
                    field.label
                }}</label>

                <!-- Text/Email/Number/Date Inputs -->
                <input v-if="
                    ['text', 'email', 'number', 'date'].includes(
                        field.type,
                    )
                " v-model="form[field.name]" :type="field.type" :placeholder="field.placeholder" :step="field.step"
                    class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                    :class="{ 'border-red-500': form.errors[field.name] }" required />

                <!-- Select Inputs -->
                <select v-else-if="field.type === 'select'" v-model="form[field.name]"
                    class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                    :class="{ 'border-red-500': form.errors[field.name] }" required>
                    <option v-if="field.placeholder" value="">
                        {{ field.placeholder }}
                    </option>

                    <!-- Department options -->
                    <template v-if="field.name === 'department_id'">
                        <option v-for="dept in field.options.value" :key="dept.id" :value="dept.id">
                            {{ dept.name }}
                        </option>
                    </template>

                    <!-- Role options -->
                    <template v-else-if="field.name === 'role'">
                        <option v-for="opt in field.options" :key="opt.value" :value="opt.value">
                            {{ opt.label }}
                        </option>
                    </template>

                    <!-- Simple string arrays -->
                    <template v-else>
                        <option v-for="opt in field.options" :key="opt" :value="opt">
                            {{ opt }}
                        </option>
                    </template>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="col-span-1 sm:col-span-2 mt-6">
                <button type="submit"
                    class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="form.processing">
                    {{
                        form.processing ? "Updating..." : "Update Employee"
                    }}
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}

.fade-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
