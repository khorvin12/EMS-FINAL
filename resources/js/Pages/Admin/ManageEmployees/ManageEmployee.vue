<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
  departments: Array,
  employees: Object,
});

const search = ref("");

const filteredEmployees = computed(() => {
  if (!search.value) return props.employees.data;

  const searchLower = search.value.toLowerCase();
  return props.employees.data.filter(
    (employee) =>
      employee.name.toLowerCase().includes(searchLower) ||
      employee.id.toString().includes(search.value)
  );
});

const tableColumns = [
  { label: "ID", key: "id" },
  { label: "Name", key: "name" },
  { label: "Department", key: "department" },
  { label: "Action", key: "actions", align: "center" },
];

const actionButtons = [
  {
    label: "Edit",
    href: (id) => `/edit/${id}`,
    color: "bg-yellow-500 hover:bg-yellow-600",
  },
  {
    label: "Delete",
    href: (id) => `/delete/${id}`,
    color: "bg-red-500 hover:bg-red-600",
    method: "delete",
    as: "button",
  },
];

const paginationButtons = computed(() => [
  {
    enabled: !!props.employees.prev_page_url,
    href: props.employees.prev_page_url,
    label: "‹",
  },
  {
    enabled: !!props.employees.next_page_url,
    href: props.employees.next_page_url,
    label: "›",
  },
]);

const showaddemployeeform = ref(false);
const showemployee = ref(false);

const form = useForm({
  first_name: "",
  last_name: "",
  employee_id: "",
  email: "",
  phone: "",
  department_id: "",
  dob: "",
  gender: "",
  civil_status: "",
  role: "employee",
  hire_date: "",
  salary: "",
});

function submit() {
  form.post("/employees", {
    preserveScroll: true,
    onSuccess: () => {
      showaddemployeeform.value = false;
      form.reset();
    },
  });
}

function formatDate(date) {
  if (!date) return "N/A";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}

const formFields = [
  { name: "last_name", label: "Last Name", type: "text", placeholder: "Last Name" },
  { name: "first_name", label: "First Name", type: "text", placeholder: "First Name" },
  { name: "employee_id", label: "Employee ID", type: "text", placeholder: "Enter ID" },
  { name: "email", label: "Email", type: "email", placeholder: "Enter Email" },
  { name: "phone", label: "Phone", type: "text", placeholder: "Enter Phone" },
  {
    name: "department_id",
    label: "Department",
    type: "select",
    options: computed(() => props.departments),
    placeholder: "Select Department",
  },
  { name: "dob", label: "Date of Birth", type: "date" },
  {
    name: "gender",
    label: "Gender",
    type: "select",
    options: ["Male", "Female"],
    placeholder: "Select Gender",
  },
  {
    name: "civil_status",
    label: "Civil Status",
    type: "select",
    options: ["Single", "Married"],
    placeholder: "Select Status",
  },
  {
    name: "role",
    label: "Role",
    type: "select",
    options: [
      { value: "employee", label: "Employee" },
      { value: "hr", label: "HR" },
      { value: "admin", label: "Admin" },
    ],
  },
  { name: "hire_date", label: "Hire Date", type: "date" },
  { name: "salary", label: "Salary", type: "number", placeholder: "Enter Salary" },
];

const employeeFields = [
  { label: "Employee ID", value: (emp) => emp.employee_id },
  { label: "Name", value: (emp) => emp.name },
  { label: "Email", value: (emp) => emp.email },
  { label: "Phone", value: (emp) => emp.phone },
  { label: "Department", value: (emp) => emp.department?.name ?? "N/A" },
  { label: "Role", value: (emp) => emp.role, class: "capitalize" },
  { label: "Date of Birth", value: (emp) => formatDate(emp.dob) },
  { label: "Gender", value: (emp) => emp.gender },
  { label: "Civil Status", value: (emp) => emp.civil_status },
  { label: "Hire Date", value: (emp) => formatDate(emp.hire_date) },
  {
    label: "Salary",
    value: (emp) =>
      `₱${Number(emp.salary).toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      })}`,
  },
];
</script>

<template>
  <div>
    <main>
      <h1 class="text-3xl font-bold text-center mb-12">Manage Employees</h1>

      <div class="flex justify-between items-center mb-4">
        <input
          v-model="search"
          type="text"
          placeholder="Search by Name or ID"
          class="px-4 py-2 border border-gray-300 rounded-md bg-white"
        />
        <button
          @click="showaddemployeeform = true"
          class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-md font-semibold"
        >
          Add New Employee
        </button>
      </div>

      <!-- Add Employee Modal -->
      <div
        v-if="showaddemployeeform"
        @click="showaddemployeeform = false"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
      >
        <div
          @click.stop
          class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto"
        >
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Add New Employee</h2>
            <button
              @click="showaddemployeeform = false"
              class="text-gray-500 hover:text-gray-700 text-2xl"
            >
              ✕
            </button>
          </div>

          <div
            v-if="$page.props.flash?.success"
            class="text-green-500 mb-4 p-3 bg-green-50 rounded"
          >
            {{ $page.props.flash.success }}
          </div>
          <div
            v-if="form.errors && Object.keys(form.errors).length"
            class="text-red-500 mb-4 p-3 bg-red-50 rounded"
          >
            <ul class="list-disc list-inside">
              <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
            </ul>
          </div>

          <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">
            <div v-for="field in formFields" :key="field.name">
              <label class="text-sm font-semibold">{{ field.label }}</label>
              <input
                v-if="['text', 'email', 'number', 'date'].includes(field.type)"
                :type="field.type"
                v-model="form[field.name]"
                :placeholder="field.placeholder"
                class="w-full mt-1 border rounded px-3 py-2"
                :class="{ 'border-red-500': form.errors[field.name] }"
                required
              />
              <select
                v-else-if="field.type === 'select'"
                v-model="form[field.name]"
                class="w-full mt-1 border rounded px-3 py-2"
                :class="{ 'border-red-500': form.errors[field.name] }"
                required
              >
                <option v-if="field.placeholder" value="" disabled>
                  {{ field.placeholder }}
                </option>
                <template v-if="field.name === 'department_id'">
                  <option
                    v-for="dept in field.options.value"
                    :key="dept.id"
                    :value="dept.id"
                  >
                    {{ dept.name }}
                  </option>
                </template>
                <template v-else-if="field.name === 'role'">
                  <option
                    v-for="opt in field.options"
                    :key="opt.value"
                    :value="opt.value"
                  >
                    {{ opt.label }}
                  </option>
                </template>
                <template v-else>
                  <option v-for="opt in field.options" :key="opt" :value="opt">
                    {{ opt }}
                  </option>
                </template>
              </select>
            </div>
            <div class="col-span-2 mt-6">
              <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold disabled:opacity-50"
                :disabled="form.processing"
              >
                {{ form.processing ? "Submitting..." : "Confirm" }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-left">
          <thead class="bg-gray-400">
            <tr>
              <th
                v-for="column in tableColumns"
                :key="column.key"
                :class="['px-6 py-4', column.align === 'center' ? 'text-center' : '']"
              >
                {{ column.label }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="employee in filteredEmployees"
              :key="employee.id"
              class="border-b border-gray-200 hover:bg-gray-50"
            >
              <td class="px-6 py-3">{{ employee.id }}</td>
              <td class="px-6 py-3">{{ employee.name }}</td>
              <td class="px-6 py-3">{{ employee.department?.name ?? "N/A" }}</td>
              <td class="py-4 px-6">
                <div class="flex justify-center gap-2">
                  <button
                    @click="showemployee = employee"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow"
                  >
                    View
                  </button>
                  <Link
                    v-for="action in actionButtons"
                    :key="action.label"
                    :href="action.href(employee.id)"
                    :method="action.method"
                    :as="action.as"
                    :class="[
                      action.color,
                      'text-white px-5 py-2 rounded-md inline-block shadow',
                    ]"
                  >
                    {{ action.label }}
                  </Link>
                </div>
              </td>
            </tr>
            <tr v-if="filteredEmployees.length === 0">
              <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                No employees found
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-end items-center mt-4 space-x-2">
        <Link
          v-if="paginationButtons[0].enabled"
          <
          :href="paginationButtons[0].href"
          class="w-8 h-8 flex items-center justify-center bg-gray-800 text-white rounded-full hover:bg-gray-700"
          >{{ paginationButtons[0].label }}</Link
        >
        <button
          v-else
          disabled
          class="w-8 h-8 flex items-center justify-center bg-gray-300 text-gray-500 rounded-full cursor-not-allowed"
        >
          {{ paginationButtons[0].label }}
        </button>
        <span class="font-semibold">{{ employees.current_page }}</span>
        <span class="text-gray-500">of {{ employees.last_page }}</span>
        <Link
          v-if="paginationButtons[1].enabled"
          :href="paginationButtons[1].href"
          class="w-8 h-8 flex items-center justify-center bg-gray-800 text-white rounded-full hover:bg-gray-700"
          >{{ paginationButtons[1].label }}</Link
        >
        <button
          v-else
          disabled
          class="w-8 h-8 flex items-center justify-center bg-gray-300 text-gray-500 rounded-full cursor-not-allowed"
        >
          {{ paginationButtons[1].label }}
        </button>
      </div>
    </main>

    <!-- View Employee Modal -->
    <div
      v-if="showemployee"
      @click="showemployee = false"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    >
      <div
        @click.stop
        class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md max-h-[90vh] overflow-y-auto"
      >
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-2xl font-bold capitalize">{{ showemployee.role }} Details</h2>
        </div>
        <div
          v-for="field in employeeFields"
          :key="field.label"
          class="mb-2 rounded-md border-l-4 border-gray-300 pl-1 border-b-4 border"
        >
          <p class="text-lg font-bold">{{ field.label }}</p>
          <p class="mt-1" :class="field.class">{{ field.value(showemployee) }}</p>
        </div>
      </div>
    </div>
  </div>
  <div class="mt-12 text-center text-gray-500">
    &copy; {{ new Date().getFullYear() }} Khorvin Company. All rights reserved.
  </div>
</template>
