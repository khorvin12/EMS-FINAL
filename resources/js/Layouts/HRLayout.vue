<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const page = usePage()
const isLoggingOut = ref(false)
const sidebarOpen = ref(false)

const user = computed(() => page.props.auth?.user)
const isActive = (path) => page.url.startsWith(path)

const handleLogout = () => {
    isLoggingOut.value = true
    router.post('/logout')
}

const navItems = [
    { name: 'Dashboard',  href: '/hr/dashboard',   icon: 'fa-gauge' },
    { name: 'Attendance', href: '/hr/attendance',   icon: 'fa-calendar-check' },
    { name: 'Leaves',     href: '/hr/leaves',       icon: 'fa-calendar-xmark' },
    { name: 'Salary',     href: '/hr/salary',       icon: 'fa-peso-sign' },
    { name: 'Settings',   href: '/hr/settings',     icon: 'fa-gear' },
]
</script>

<template>
    <div class="flex flex-col h-screen overflow-hidden bg-slate-50">

        <!-- Top Header -->
        <header class="bg-blue-800 text-white shadow-md z-30 flex-shrink-0">
            <nav class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="md:hidden p-2 rounded-lg hover:bg-blue-700 transition"
                        aria-label="Toggle menu"
                    >
                        <i :class="sidebarOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-lg"></i>
                    </button>
                    <div class="flex items-center gap-2">
                        <div class="bg-white/20 rounded-lg p-1.5">
                            <i class="fa-solid fa-people-roof text-white text-sm"></i>
                        </div>
                        <span class="text-lg font-bold tracking-wide hidden sm:block">HR Portal</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2 bg-white/10 rounded-lg px-3 py-1.5">
                        <div class="w-7 h-7 bg-white/30 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-user text-xs"></i>
                        </div>
                        <span class="text-sm font-medium">{{ user ? `${user.first_name} ${user.last_name}` : 'HR Manager' }}</span>
                    </div>
                    <button
                        @click="handleLogout"
                        :disabled="isLoggingOut"
                        class="bg-blue-900 hover:bg-blue-950 px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2 disabled:opacity-60"
                    >
                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                        <span>{{ isLoggingOut ? 'Logging out…' : 'Logout' }}</span>
                    </button>
                </div>
            </nav>
        </header>

        <div class="flex flex-1 overflow-hidden relative">

            <div
                v-if="sidebarOpen"
                @click="sidebarOpen = false"
                class="fixed inset-0 bg-black/40 z-20 md:hidden"
            ></div>

            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
                class="fixed md:static z-20 h-full bg-gray-900 text-white w-64 flex-shrink-0 flex flex-col transition-transform duration-300 ease-in-out"
            >
                <div class="px-6 py-5 border-b border-white/10">
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">EMS Portal</p>
                    <p class="text-sm font-medium text-white mt-0.5">HR Panel</p>
                </div>

                <nav class="flex-1 py-6 space-y-1 px-3 overflow-y-auto">
                    <Link
                        v-for="item in navItems"
                        :key="item.href"
                        :href="item.href"
                        @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-150"
                        :class="isActive(item.href)
                            ? 'bg-blue-600 text-white shadow-md shadow-blue-900/40'
                            : 'text-gray-300 hover:bg-white/8 hover:text-white'"
                    >
                        <i :class="['fa-solid fa-fw', item.icon]" class="w-4 text-center"></i>
                        <span>{{ item.name }}</span>
                    </Link>
                </nav>

                <div class="px-6 py-4 border-t border-white/10">
                    <p class="text-xs text-gray-500">Employee Management System</p>
                </div>
            </aside>

            <main class="flex-1 overflow-y-auto p-6 md:p-10">
                <slot />
            </main>
        </div>
    </div>
</template>