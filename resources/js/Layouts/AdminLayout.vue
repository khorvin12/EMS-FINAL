<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const isActive = (path) => page.url.startsWith(path)
</script>


<template>
    <div class="flex flex-col h-screen">
        <!-- Top Bar-->
        <header class="bg-red-800 text-white">
            <nav class="flex items-center justify-between p-6 font-bold">
                <h1 class="ml-14 text-3xl">Admin</h1>

                <div class="ml-auto mr-6">
                    <button
                        class="w-8 h-8 overflow-auto rounded-full bg-slate-100 focus:outline-none focus:ring-1 focus:ring-slate-100 focus:ring-offset-2 focus:ring-offset-slate-800"><img
                            src="https://picsum.photos/200" alt=""></button>
                </div>

                <div class="bg-red-700 hover:bg-red-600 rounded-md px-2 text-xl">
                    <Link href="/dashboard">Logout</Link>
                </div>
            </nav>
        </header>

        <div class="flex flex-1">
            <!-- Side Bar -->
            <aside class="bg-gray-800 text-white flex justify-center w-64 py-10 text-xl">
                <nav class="space-y-8">
                    <Link href="/dashboard" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/dashboard')
                        ? 'bg-red-700 font-bold'
                        : 'hover:bg-red-700'">
                        <i class="fa fa-tachometer" aria-hidden="true"></i>
                        <span>Dashboard</span>
                    </Link>

                    <Link href="/manageemployee" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/manageemployee')
                        ? 'bg-red-700 font-bold'
                        : 'hover:bg-red-700'">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>Employees</span>
                    </Link>

                    <Link href="/managedepartment" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/managedepartment')
                        ? 'bg-red-700 font-bold'
                        : 'hover:bg-red-700'">
                        <i class="fa fa-building" aria-hidden="true"></i>
                        <span>Departments</span>
                    </Link>

                    <Link href="/leaves" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/leaves')
                        ? 'bg-red-700 font-bold'
                        : 'hover:bg-red-700'">
                        <i class="fa fa-calendar-times-o" aria-hidden="true">&#xf273;</i> 
                        <span>Leaves</span>
                    </Link>

                    <Link href="/settings" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/settings')
                        ? 'bg-red-700 font-bold'
                        : 'hover:bg-red-700'">
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