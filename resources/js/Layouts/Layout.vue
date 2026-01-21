<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const isActive = (path) => page.url.startsWith(path)
</script>


<template>
    <div class="flex flex-col h-screen">
        <!-- Top Bar-->
        <header class="bg-red-800 text-white">
            <nav class="flex items-center justify-between text-2xl p-6 font-bold">
                <h1 class="ml-14">Admin</h1>
                <div class="space-x-6">
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
                        <i class="fa fa-tachometer" />
                        <span>Dashboard</span>
                    </Link>

                    <Link href="/manageemployee" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/manageemployee')
                        ? 'bg-red-700 font-bold'
                        : 'hover:bg-red-700'">
                        <i class="fa fa-users" />
                        <span>Employees</span>
                    </Link>

                    <Link href="/managedepartment" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/managedepartment')
                        ? 'bg-red-700 font-bold'
                        : 'hover:bg-red-700'">
                        <i class="fa fa-building" />
                        <span>Departments</span>
                    </Link>

                    <Link href="/manageleaves" class="flex items-center space-x-4 py-2.5 px-8 rounded-md" :class="isActive('/manageleaves')
                        ? 'bg-red-700 font-bold'
                        : 'hover:bg-red-700'">
                        <i class="fa fa-calendar" />
                        <span>Leaves</span>
                    </Link>

                    <div class="rounded-md p-2 hover:bg-red-700 flex items-center space-x-4 py-2.5 px-8">
                        <i class="fa fa-cog" aria-hidden="true" />
                        <Link href="/adminsettings">Settings</Link>
                    </div>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-8">
                <slot />
            </main>
        </div>
    </div>
</template>