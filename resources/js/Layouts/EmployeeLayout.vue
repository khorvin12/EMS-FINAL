<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const page = usePage()

const isLoggingOut = ref(false) // Tracks if we are currently logging out

// Logged-in user
const user = computed(() => page.props.auth?.user)

// Better active state logic
const isActive = (path) => page.url.startsWith(path)

// Use Inertia for logout to maintain SPA state
const handleLogout = () => {
    isLoggingOut.value = true
    router.post('/logout')
}
</script>

<template>
    <!-- Top Bar -->
    <div class="flex flex-col h-screen">
        <header class="bg-green-600 text-white shadow-md">
            <nav class="flex items-center justify-between font-bold p-6">

                <!-- Username -->
                <span class="text-3xl">
                    {{ user?.name ?? 'Employee' }}
                </span>

                <!-- Logout Button -->
                <button @click="handleLogout" :disabled="isLoggingOut"
                    class="bg-red-600 hover:bg-red-500 px-6 py-2 rounded-lg transition">
                    {{ isLoggingOut ? 'Logging out...' : 'Logout' }}
                </button>

            </nav>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <aside class="bg-gray-800 text-white w-64 py-10 text-xl hidden md:block">
                <nav class="space-y-8 px-4">
                    <Link v-for="item in [
                        { name: 'Dashboard', href: '/employee/dashboard', icon: 'fa-tachometer' },
                        { name: 'Attendance', href: '/employee/attendance', icon: 'fa-calendar-check' },
                        { name: 'Leaves', href: '/employee/leaves', icon: 'fa-calendar-xmark' },
                        { name: 'Salary', href: '/employee/salary', icon: 'fa-dollar' },
                        { name: 'Settings', href: '/employee/settings', icon: 'fa-cog' },
                    ]" :key="item.href" :href="item.href"
                        class="flex items-center space-x-4 py-2.5 px-6 rounded-md transition-colors"
                        :class="isActive(item.href) ? 'bg-green-500 font-bold' : 'hover:bg-green-600/50'">

                        <i :class="['fa', item.icon]" aria-hidden="true"></i>

                        <span>{{ item.name }}</span>
                    </Link>
                </nav>
            </aside>

            <!-- Main Contents -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-8 md:p-12">
                <div class="max-w-8xl mx-auto">
                    <slot />
                </div>
            </main>

        </div>
    </div>
</template>