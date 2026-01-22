<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()
const role = page.props.userRole as string

const showReject = ref(false)

const rejectAction = () => {
  showReject.value = true
  setTimeout(() => {
    showReject.value = false
  }, 2000)
}
</script>

<template>
    <!-- Contents -->
    <main>
        <!-- Title Contents -->
        <h1 class="flex justify-center text-3xl font-bold mb-10">Leaves</h1>

        <!-- Leaves Table Component -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto p-6">
            <table class="text-1xl min-w-full text-left">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-gray-800 font-bold">No.</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-gray-800 font-bold">Leave Type</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-gray-800 font-bold">Start Date</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-gray-800 font-bold">End Date</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-gray-800 font-bold flex justify-center">Status</th>
                        <th class="px-6 py-3 border-b-2 text-center border-gray-300 text-gray-800 font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr bg-green-200 class="hover:bg-gray-100">
                        <td class="px-6 py-4 border-b border-gray-300">006</td>
                        <td class="px-6 py-4 border-b border-gray-300">Magic Chess</td>
                        <td class="px-6 py-4 border-b border-gray-300">1/1/2001</td>
                        <td class="px-6 py-4 border-b border-gray-300">1/1/2002</td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            <span class="bg-green-400 py-4 flex justify-around rounded-md text-sm shadow-md font-bold hover:py-7 transition-all duration-100">Approved</span>
                        </td>
                        <td class="px-6 py-4 text-center border-b border-gray-300">
                        <div class="flex justify-center gap-2">
    <Link href="/AdminLeavesReview">
        <button
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md"
        >
            Review
        </button>
    </Link>

    <button
        @click="rejectAction"
        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-md"
    >
        Reject
    </button>
</div>

<!-- Reject Success Overlay -->
<transition
    enter-active-class="transition duration-300 ease-out"
    enter-from-class="opacity-0 scale-90"
    enter-to-class="opacity-100 scale-100"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="opacity-100 scale-100"
    leave-to-class="opacity-0 scale-90"
>
    <div
        v-if="showReject"
        class="fixed inset-0 flex items-center justify-center z-50"
    >
        <div class="bg-red-500 px-6 py-4 rounded-lg shadow-xl text-green-600 text-xl font-bold">
            Rejected successfully ✅
        </div>
    </div>
</transition>
                        </td>
                    </tr>
                </tbody>
                <tbody>
                    <tr bg-green-200 class="hover:bg-gray-100">
                        <td class="px-6 py-4 border-b border-gray-300">007</td>
                        <td class="px-6 py-4 border-b border-gray-300">Fever</td>
                        <td class="px-6 py-4 border-b border-gray-300">1/1/2001</td>
                        <td class="px-6 py-4 border-b border-gray-300">1/1/2002</td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            <span class="bg-yellow-400 py-4 rounded-md text-sm shadow-md font-bold flex justify-center">Pending</span>
                        </td>
                        <td class="px-6 py-4 text-center border-b border-gray-300">
                        <div class="flex justify-center gap-2">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md">Review</button>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-md">Reject</button>
                        </div>
                        </td>
                    </tr>
                </tbody>
                <tbody>
                    <tr bg-green-200 class="hover:bg-gray-100">
                        <td class="px-6 py-4 border-b border-gray-300">008</td>
                        <td class="px-6 py-4 border-b border-gray-300">Almoranas</td>
                        <td class="px-6 py-4 border-b border-gray-300">1/1/2001</td>
                        <td class="px-6 py-4 border-b border-gray-300">1/1/2002</td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            <span class="bg-red-400 px-7 py-4 rounded-md text-sm shadow-md font-bold flex justify-around">Rejected</span>
                        </td>
                        <td class="px-6 py-4 text-center border-b border-gray-300">
                        <div class="flex justify-center gap-2">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md">Review</button>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-md">Reject</button>
                        </div>
                        </td>
                    </tr>
                </tbody>
                <tbody>
                    <tr bg-green-200 class="hover:bg-gray-100">
                        <td class="px-6 py-4 border-b border-gray-300">009</td>
                        <td class="px-6 py-4 border-b border-gray-300">Diabetes</td>
                        <td class="px-6 py-4 border-b border-gray-300">1/1/2001</td>
                        <td class="px-6 py-4 border-b border-gray-300">1/1/2002</td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            <span class="bg-green-400 px-6 py-4 rounded-md text-sm shadow-md font-bold flex justify-around">Approved</span>
                        </td>
                        <td class="px-6 py-4 text-center border-b border-gray-300">
                        <div class="flex justify-center gap-2">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 shadow-md px-4 rounded">Review</button>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 shadow-md px-4 rounded">Reject</button>
                        </div>
                        </td>
                    </tr>
                </tbody>
                <tbody>
                    <tr bg-green-200 class="hover:bg-gray-100">
                        <td class="px-6 py-4 border-b border-gray-300">010</td>
                        <td class="px-6 py-4 border-b border-gray-300">ML sir</td>
                        <td class="px-6 py-4 border-b border-gray-300">1/1/2001</td>
                        <td class="px-6 py-4 border-b border-gray-300">1/1/2002</td>
                        <td class="px-6 py-4 border-b border-gray-300">
                            <span class="bg-green-400 text-black px-6 py-4 rounded-md text-sm shadow-md font-bold flex justify-around">Approved</span>
                        </td>
                        <td class="px-6 py-4 text-center border-b border-gray-300">
                        <div class="flex justify-center gap-2">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 shadow-md px-4 rounded">Review</button>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 shadow-md px-4 rounded">Reject</button>
                        </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <main class="flex justify-center items-center h-screen">
        <h1 class="text-4xl font-bold">Access Denied</h1>
    </main>
</template>
