<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const page = usePage()

// Logged-in user
const user = computed(() => page.props.auth?.user)

// Logout user
const isLoggingOut = ref(false)

const isActive = (path) => page.url.startsWith(path)

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

                <span class="text-3xl">
                    {{ user?.name ?? 'HR Manager' }}
                </span>

                <button @click="handleLogout" :disabled="isLoggingOut"
                    class="bg-red-600 hover:bg-red-500 px-6 py-2 rounded-lg transition">
                    {{ isLoggingOut ? 'Logging out...' : 'Logout' }}
                </button>

            </nav>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <aside class="bg-gray-900 text-white w-64 py-10 text-xl hidden md:block">
                <nav class="space-y-6 px-4">
                    <Link v-for="item in [
                        { name: 'Dashboard', href: '/hr/dashboard', icon: 'fa-tachometer' },
                        { name: 'Attendance', href: '/hr/attendance', icon: 'fa-calendar-check' },
                        { name: 'Leaves', href: '/hr/leaves', icon: 'fa-calendar-xmark' },
                        { name: 'Salaries', href: '/hr/salaries', icon: 'fa-dollar' },
                        { name: 'Settings', href: '/hr/settings', icon: 'fa-cog' },
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
                <div class="max-w-8xl mx-auto p-8 md:p-16">
                    <slot />
                </div>
            </main>

        </div>
    </div>
</template>