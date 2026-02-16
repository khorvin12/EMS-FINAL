<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const isActive = (path) => page.url.startsWith(path)
</script>

<template>
    <div class="flex flex-col h-screen">
        <!-- Top Bar-->
        <header class="bg-blue-700 text-white">
            <nav class="flex items-center justify-between text-2xl p-6 font-bold">
                <h1 class="ml-14">HR</h1>
                <div class="space-x-6">
                    <Link href="/logout" method="post" as="button">Logout</Link>
                </div>
            </nav>
        </header>

        <div class="flex flex-1">
            <!-- Side Bar -->
            <aside class="bg-gray-800 text-white flex justify-center w-64 py-10 text-xl">
                <nav class="space-y-8">
                    <Link href="/hr/index" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/hr/index')
                        ? 'bg-blue-600 font-bold'
                        : 'hover:bg-blue-700'">
                        <i class="fa fa-dashboard" aria-hidden="true"></i>
                        <span>Dashboard</span>
                    </Link>

                    <Link href="/hr/attendance" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/hr/attendance')
                        ? 'bg-blue-600 font-bold'
                        : 'hover:bg-blue-700'">
                        <i class="fa fa-calendar-check-o" aria-hidden="true">&#xf274;</i>
                        <span>Attendance</span>
                    </Link>

                    <Link href="/hr/leaves" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/hr/leaves')
                        ? 'bg-blue-600 font-bold'
                        : 'hover:bg-blue-700'">
                        <i class="fa fa-calendar-times-o" aria-hidden="true">&#xf273;</i>
                        <span>Leaves</span>
                    </Link>

                    <Link href="/hr/salaries" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/hr/salaries')
                        ? 'bg-blue-600 font-bold'
                        : 'hover:bg-blue-700'">
                        <i class="fa fa-dollar" aria-hidden="true"></i>
                        <span>Salary</span>
                    </Link>

                    <Link href="/hr/settings" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/hr/settings')
                        ? 'bg-blue-600 font-bold'
                        : 'hover:bg-blue-700'">
                        <i class="fa fa-cog" aria-hidden="true"></i>
                        <span>Settings</span>
                    </Link>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-8">
                <slot />
            </main>
        </div>
    </div>
</template>