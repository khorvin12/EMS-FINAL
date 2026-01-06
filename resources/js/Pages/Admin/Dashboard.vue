<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div class="flex flex-col h-screen ">

        <!-- Top Bar-->
        <header class="bg-red-800 text-white">
            <nav class="flex items-center justify-between text-2xl p-6 font-bold">
                <h1 class="ml-12">Admin</h1>
                <div class="space-x-6">
                    <Link href="/dashboard">Logout</Link>
                </div>
            </nav>
        </header>

        <div class="flex flex-1">

            <!-- Side Bar -->
            <aside class="w-64 bg-gray-800 text-white flex justify-center py-6 text-xl">
                <nav class="space-y-2">
                    <div class="rounded-md p-2 mb-6 text-center hover:bg-red-700">
                        <Link href="/dashboard"><i class="fa fa-tachometer" aria-hidden="true" />Dashboard</Link>
                    </div>

                    <div class="rounded-md p-2 mb-6 text-center hover:bg-red-700">
                        <Link href="/employees"><i class="fa fa-users" aria-hidden="true" />Employees</Link>
                    </div>

                    <div class="rounded-md p-2 mb-6 text-center hover:bg-red-700">
                        <Link href="/departments"><i class="fa fa-building" aria-hidden="true" />Departments</Link>
                    </div>

                    <div class="rounded-md p-2 mb-6 text-center hover:bg-red-700">
                        <Link href="/leaves"><i class="fa fa-calendar" aria-hidden="true" />Leaves</Link>
                    </div>

                    <div class="rounded-md p-2 mb-6 text-center hover:bg-red-700">
                        <Link href="/settings"><i class="fa fa-cog" aria-hidden="true" />Settings</Link>
                    </div>
                </nav>
            </aside>

            <!-- Contents -->
            <main class="flex-1 p-8 overflow-y-auto bg-slate-200">
                <h1 class="text-3xl font-bold mb-6">Dashboard Overview</h1>

                <!-- Top -->
                <div class="grid grid-cols-3 gap-6 mb-12 text-center">

                    <!-- Total Employees -->
                    <div class="bg-stone-50 rounded-md inline-flex items-center pe-5 mx-auto">
                        <div class="bg-green-500 rounded-md px-4 p-2">
                            <i class="fa fa-users fa-2x" aria-hidden="true" />
                        </div>

                        <div class="m-2 text-lg">
                            <p>Total Employees</p>
                        </div>
                    </div>

                    <!-- Total Departments -->
                    <div class="bg-stone-50 rounded-md inline-flex items-center pe-5 mx-auto">
                        <div class="bg-green-500 rounded-md px-6 p-2">
                            <i class="fa fa-building fa-2x" aria-hidden="true" />
                        </div>

                        <div class="m-2 text-lg">
                            <p>Total Departments</p>
                        </div>
                    </div>

                    <!-- Monthly Pay -->
                    <div class="bg-stone-50 rounded-md inline-flex items-center pe-5 mx-auto">
                        <div class="bg-green-500 rounded-md px-6 py-2">
                            <i class="fa fa-usd fa-2x" aria-hidden="true" />
                        </div>

                        <div class="m-2 text-lg">
                            <p>Monthly Pay</p>
                        </div>
                    </div>

                </div>

                <!-- Leave Details -->
                <h2 class="text-2xl font-bold mb-6 mt-64 text-center">Leave Details</h2>

                <div class="grid grid-cols-3 gap-6 mb-12 text-center">

                    <!-- Leave Pending -->
                    <div class="bg-stone-50 rounded-md inline-flex items-center pe-5 mx-auto">
                        <div class="bg-yellow-400 rounded-md px-6 py-2">
                            <i class="fa fa-hourglass-half fa-2x" aria-hidden="true" />
                        </div>

                        <div class="m-2 text-lg">
                            <p>Leave Pending</p>
                        </div>
                    </div>

                    <!-- Leave Approved -->
                    <div class="bg-stone-50 rounded-md inline-flex items-center pe-5 mx-auto">
                        <div class="bg-green-500 rounded-md px-6 py-2">
                            <i class="fa fa-check-circle fa-2x" aria-hidden="true" />
                        </div>

                        <div class="m-2 text-lg">
                            <p>Leave Approved</p>
                        </div>
                    </div>

                    <!-- Leave Rejected -->
                    <div class="bg-stone-50 rounded-md inline-flex items-center pe-5 mx-auto">
                        <div class="bg-red-500 rounded-md px-6 py-2">
                            <i class="fa fa-times-circle fa-2x" aria-hidden="true" />
                        </div>

                        <div class="m-2 text-lg">
                            <p>Leave Rejected</p>
                        </div>
                    </div>

                </div>

            </main>
        </div>
    </div>
</template>