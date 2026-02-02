<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()
const isLoggingOut = ref(false) // Tracks if we are currently logging out
const dropdownOpen = ref(false) // Tracks if the user menu is open or closed

// Active state logic
const isActive = (path) => page.url.startsWith(path)

// Logout handling
const handleLogout = () => {
    isLoggingOut.value = true
    router.post('/logout')
}
</script>

<template>
    <!-- Top Bar -->
    <div class="flex flex-col h-screen overflow-hidden">
        <header class="bg-blue-800 text-white shadow-md z-20">
            <nav class="flex items-center justify-between p-6 font-bold">

                <h1 class="text-3xl ml-4">HR</h1>

                <div class="mr-6 relative">
                    <!-- Dropdown menu button -->
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="relative z-50 w-10 h-10 overflow-hidden rounded-full bg-slate-100 focus:ring-2 focus:ring-white">
                        <img src="https://picsum.photos/200" alt="HR User">
                    </button>

                    <!-- Dropdown Menu -->
                    <div v-if="dropdownOpen">
                        <div class="fixed inset-0 z-40 cursor-default" @click="dropdownOpen = false"></div>

                        <div
                            class="bg-white shadow-xl absolute top-full mt-2 right-0 rounded-lg overflow-hidden font-light text-xl z-50 min-w-[200px]">

                            <!-- Username -->
                            <div
                                class="text-black text-center font-medium px-8 py-3 border-b uppercase text-sm tracking-wider">
                                HR Manager
                            </div>

                            <!-- Profile Link -->
                            <Link href="/profile" class="block px-8 py-2 text-center text-black hover:bg-gray-100">
                                Profile
                            </Link>

                            <!-- Logout Button -->
                            <button @click="handleLogout" :disabled="isLoggingOut"
                                class="w-full px-8 py-2 text-center text-red-600 hover:bg-gray-100 disabled:opacity-50">
                                {{ isLoggingOut ? 'Logging out...' : 'Logout' }}
                            </button>

                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <aside class="bg-gray-900 text-white w-64 py-10 text-xl hidden md:block">
                <nav class="space-y-6 px-4">
                    <Link v-for="item in [
                        { name: 'Dashboard', href: '/index', icon: 'fa-tachometer' },
                        { name: 'Attendance', href: '/attendance', icon: 'fa-calendar-check' },
                        { name: 'Leaves', href: '/leaves', icon: 'fa-calendar-xmark' },
                        { name: 'Salary', href: '/salary', icon: 'fa-dollar' },
                        { name: 'Settings', href: '/settings', icon: 'fa-cog' },
                    ]" :key="item.href" :href="item.href"
                        class="flex items-center space-x-4 py-3 px-6 rounded-md transition-all"
                        :class="isActive(item.href) ? 'bg-blue-600 font-bold shadow-md' : 'hover:bg-blue-700/50'">

                        <i :class="['fa-solid fa-fw text-xl', item.icon]" aria-hidden="true"></i>

                        <span>{{ item.name }}</span>
                    </Link>
                </nav>
            </aside>

            <!-- Main Contents -->
            <main class="flex-1 overflow-y-auto bg-gray-100">
                <div class="max-w-7xl mx-auto p-8 md:p-16">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>