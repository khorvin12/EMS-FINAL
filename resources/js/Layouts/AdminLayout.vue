<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const page = usePage()
const isLoggingOut = ref(false) // Tracks if we are currently logging out

// Logged-in user
const user = computed(() => page.props.auth?.user)

// 
const isActive = (path) => page.url.startsWith(path)

// Logout logic
const handleLogout = () => {
    isLoggingOut.value = true
    router.post('/logout')
}
</script>

<template>
    <!-- Top Bar -->
    <div class="flex flex-col h-screen overflow-hidden">
        <header class="bg-red-700 text-white shadow-md">
            <nav class="flex items-center justify-between font-bold p-6">

                <!-- Username -->
                <span class="text-3xl">
                    ADMINSTRATOR
                </span>

                <!-- Logout Button -->
                <button @click="handleLogout" :disabled="isLoggingOut"
                    class="bg-red-600 px-6 py-2 rounded-lg font-semibold hover:bg-red-500 transition">
                    {{ isLoggingOut ? 'Logging out...' : 'Logout' }}
                </button>

            </nav>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <aside class="bg-gray-800 text-white w-64 py-10 text-xl hidden md:block shadow-lg">
                <nav class="space-y-8 px-4">
                    <Link v-for="item in [
                        { name: 'Dashboard', href: '/dashboard', icon: 'fa-tachometer' },
                        { name: 'Employees', href: '/manageemployees', icon: 'fa-users' },
                        { name: 'Departments', href: '/departments', icon: 'fa-building' },
                        { name: 'Leaves', href: '/manageleaves/leaves', icon: 'fa-calendar-xmark' },
                        { name: 'Settings', href: '/settings', icon: 'fa-cog' },
                    ]" :key="item.href" :href="item.href"
                        class="flex items-center space-x-4 py-3 px-6 rounded-md transition-all"
                        :class="isActive(item.href) ? 'bg-red-600 font-bold shadow-md' : 'hover:bg-red-700/50'">

                        <i :class="['fa w-6', item.icon]" aria-hidden="true"></i>

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