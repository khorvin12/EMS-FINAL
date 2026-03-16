<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

interface Leave {
    id: number
    user: { name: string }
    reason: string
    start_date: string
    end_date: string
    status: string
}

const props = defineProps<{
    leave: Leave
}>()

const showSuccess = ref<'approved' | 'rejected' | null>(null)

const handleApprove = () => {
    if (confirm('Are you sure you want to approve this leave?')) {
        router.post(`/manageleaves/leaves/${props.leave.id}/approve`, {}, {
            onSuccess: () => {
                showSuccess.value = 'approved'
                setTimeout(() => {
                    showSuccess.value = null
                    router.visit('/manageleaves/leaves')
                }, 2000)
            }
        })
    }
}

const handleReject = () => {
    if (confirm('Are you sure you want to reject this leave?')) {
        router.post(`/manageleaves/leaves/${props.leave.id}/reject`, {}, {
            onSuccess: () => {
                showSuccess.value = 'rejected'
                setTimeout(() => {
                    showSuccess.value = null
                    router.visit('/manageleaves/leaves')
                }, 2000)
            }
        })
    }
}
</script>

<template>

    <Head title=" | Leave Details" />

    <div class="flex justify-center items-center py-24">

        <div class="relative bg-sky-400 rounded-lg p-6 w-full max-w-4xl shadow-lg">

            <div class="flex justify-end mb-4">
                <Link href="/manageleaves/leaves" class="text-2xl font-bold hover:text-red-600 transition">
                    ×
                </Link>
            </div>

            <div class="bg-white rounded-lg p-6">

                <div class="mb-4 p-3 bg-blue-50 rounded">
                    <p class="text-sm font-semibold">Employee: <span class="font-normal">{{ leave.user.name }}</span></p>
                    <p class="text-sm font-semibold">Status:
                        <span :class="{
                            'text-yellow-600': leave.status === 'pending',
                            'text-green-600':  leave.status === 'approved',
                            'text-red-600':    leave.status === 'rejected'
                        }">
                            {{ leave.status.toUpperCase() }}
                        </span>
                    </p>
                </div>

                <div class="flex flex-col md:flex-row gap-6 mb-6">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1">From</label>
                        <input type="text" :value="leave.start_date" readonly
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1">To</label>
                        <input type="text" :value="leave.end_date" readonly
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Reason</label>
                    <textarea rows="4" :value="leave.reason || 'No reason provided'" readonly
                        class="w-full border rounded-md px-3 py-2 text-lg resize-none border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>
            </div>

            <div class="flex justify-between mt-6 px-6">
                <button @click="handleApprove" :disabled="leave.status === 'approved'"
                    class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded shadow disabled:opacity-50 disabled:cursor-not-allowed">
                    Approve
                </button>
                <button @click="handleReject" :disabled="leave.status === 'rejected'"
                    class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded shadow disabled:opacity-50 disabled:cursor-not-allowed">
                    Reject
                </button>
            </div>
        </div>
    </div>

    <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-90"
        enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-90">
        <div v-if="showSuccess" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div :class="showSuccess === 'approved' ? 'bg-green-500' : 'bg-red-500'"
                class="px-8 py-4 rounded-lg shadow-xl text-white text-xl font-bold">
                {{ showSuccess === 'approved' ? 'Approved' : 'Rejected' }} successfully ✓
            </div>
        </div>
    </transition>
</template>