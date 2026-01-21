<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'

const page = usePage()

const isActive = (path) => page.url.startsWith(path)

function logout() {
  router.post('/logout')
}
</script>


<template>
    <div class="flex flex-col h-screen">
        <!-- Top Bar-->
        <header class="bg-green-600 text-white">
            <nav class="flex items-center justify-between text-2xl p-6 font-bold">
                <h1 class="ml-14">Employee</h1>

                <!-- Logout -->
                <button @click="logout" class="text-white font-bold">
                    Logout
                </button>
            </nav>
        </header>

        <div class="flex flex-1">
            <!-- Side Bar -->
            <aside class="bg-gray-800 text-white flex justify-center w-64 py-10 text-xl">
                <nav class="space-y-8">
                    <Link href="/dashboard" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/dashboard')
                        ? 'bg-green-600 font-bold'
                        : 'hover:bg-green-500'">
                        <i class="fa fa-dashboard" aria-hidden="true"></i>
                        <span>Dashboard</span>
                    </Link>

                    <Link href="/attendance" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/attendance')
                        ? 'bg-green-600 font-bold'
                        : 'hover:bg-green-500'">
                        <i class="fa fa-calendar-check-o" aria-hidden="true">&#xf274;</i>
                        <span>Attendance</span>
                    </Link>

                    <Link href="/leave" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/leave')
                        ? 'bg-green-600 font-bold'
                        : 'hover:bg-green-500'">
                        <i class="fa fa-calendar-times-o" aria-hidden="true">&#xf273;</i>
                        <span>Leaves</span>
                    </Link>

                    <Link href="/employee-salary" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/employee-salary')
                        ? 'bg-green-600 font-bold'
                        : 'hover:bg-green-500'">
                        <i class="fa fa-dollar" aria-hidden="true"></i>
                        <span>Salary</span>
                    </Link>

                    <Link href="/settings" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/settings')
                        ? 'bg-green-600 font-bold'
                        : 'hover:bg-green-500'">
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