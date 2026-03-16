<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';

const props = defineProps({
    attendance: { type: Object, required: true }
});

const toTimeInput = (t) => t ? t.toString().slice(0, 5) : '';

const form = useForm({
    date:      props.attendance.date      ?? '',
    check_in:  toTimeInput(props.attendance.check_in),
    check_out: toTimeInput(props.attendance.check_out),
    status:    props.attendance.status    ?? 'present',
});

const submit = () => {
    form.put(`/hr/attendance/${props.attendance.id}`);
};
</script>

<template>
    <div class="flex justify-center py-36 px-4 pb-8">

        <Head title=" | Edit Attendance" />

        <div class="bg-white border-4 border-blue-500 rounded-lg p-4 md:p-8 w-full max-w-2xl shadow-md">

            <h2 class="text-center text-xl font-bold mb-6">Attendance Record</h2>

            <form @submit.prevent="submit">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Employee ID</label>
                        <input :value="attendance.employee_id" disabled
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-gray-100 text-gray-500 cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Employee Name</label>
                        <input :value="attendance.employee_name" disabled
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-gray-100 text-gray-500 cursor-not-allowed" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Date</label>
                        <input type="date" v-model="form.date"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            :class="{ 'border-red-400': form.errors.date }" />
                        <p v-if="form.errors.date" class="text-red-500 text-xs mt-1">{{ form.errors.date }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Check In</label>
                        <input type="time" v-model="form.check_in"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            :class="{ 'border-red-400': form.errors.check_in }" />
                        <p v-if="form.errors.check_in" class="text-red-500 text-xs mt-1">{{ form.errors.check_in }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Check Out</label>
                    <input type="time" v-model="form.check_out"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="{ 'border-red-400': form.errors.check_out }" />
                    <p v-if="form.errors.check_out" class="text-red-500 text-xs mt-1">{{ form.errors.check_out }}</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <label v-for="opt in [
                            { value: 'present',  label: 'Present',  cls: 'bg-green-500 hover:bg-green-600'   },
                            { value: 'absent',   label: 'Absent',   cls: 'bg-red-500 hover:bg-red-600'       },
                            { value: 'late',     label: 'Late',     cls: 'bg-yellow-400 hover:bg-yellow-500' },
                            { value: 'on_leave', label: 'On Leave', cls: 'bg-blue-400 hover:bg-blue-500'     },
                        ]" :key="opt.value" :class="[
                            opt.cls,
                            'text-white text-sm font-semibold text-center py-2 rounded cursor-pointer transition-opacity',
                            form.status !== opt.value ? 'opacity-30' : 'opacity-100 ring-2 ring-offset-1 ring-blue-400'
                        ]">
                            <input type="radio" :value="opt.value" v-model="form.status" class="hidden" />
                            {{ opt.label }}
                        </label>
                    </div>
                    <p v-if="form.errors.status" class="text-red-500 text-xs mt-1">{{ form.errors.status }}</p>
                </div>

                <div class="flex justify-center items-center gap-4 mt-2 mb-2">
                    <Link href="/hr/attendance"
                        class="flex-1 text-center bg-gray-600 hover:bg-gray-700 text-white font-semibold px-6 md:px-8 py-2 rounded transition-colors">
                        Cancel
                    </Link>
                    <button type="submit" :disabled="form.processing"
                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 md:px-8 py-2 rounded transition-colors disabled:opacity-50">
                        {{ form.processing ? 'Saving…' : 'Save' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>