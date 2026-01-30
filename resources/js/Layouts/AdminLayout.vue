<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()
const isLoggingOut = ref(false)
const dropdownOpen = ref(false)

// Highlight the active link
const isActive = (path) => page.url.startsWith(path)

// Logout logic
const handleLogout = () => {
    isLoggingOut.value = true
    router.post('/logout')
}
</script>

<template>
    <div class="flex flex-col h-screen overflow-hidden">
        <header class="bg-red-700 text-white shadow-md z-20">
            <nav class="flex items-center justify-between p-6 font-bold">
                <h1 class="text-3xl">Admin Panel</h1>

                <div class="mr-6 relative">
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="relative z-50 w-10 h-10 overflow-hidden rounded-full bg-slate-100 focus:ring-2 focus:ring-white">
                        <img src="https://picsum.photos/200" alt="Admin Avatar">
                    </button>

                    <div v-if="dropdownOpen">
                        <div class="fixed inset-0 z-40" @click="dropdownOpen = false"></div>

                        <div class="bg-white shadow-xl absolute top-full mt-2 right-0 rounded-lg overflow-hidden font-light text-xl z-50 min-w-[200px]">
                            <div class="text-black text-center font-medium px-8 py-3 border-b uppercase text-sm tracking-wider">
                                Administrator
                            </div>
                            <Link href="/profile" class="block px-8 py-2 text-center text-black hover:bg-gray-100 font-medium">
                                Profile
                            </Link>
                            <button @click="handleLogout" :disabled="isLoggingOut"
                                class="w-full px-8 py-2 text-center text-red-600 hover:bg-gray-100 disabled:opacity-50 font-medium">
                                {{ isLoggingOut ? 'Logging out...' : 'Logout' }}
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <aside class="bg-gray-900 text-white w-64 py-10 text-xl hidden md:block shadow-lg">
                <nav class="space-y-8 px-4">
                    <Link v-for="item in [
                        { name: 'Dashboard', href: '/home', icon: 'fa-tachometer' },
                        { name: 'Employees', href: '/manageemployee', icon: 'fa-users' },
                        { name: 'Departments', href: '/managedepartment', icon: 'fa-building' },
                        { name: 'Leaves', href: '/leaves', icon: 'fa-calendar-check-o' },
                        { name: 'Settings', href: '/settings', icon: 'fa-cog' },
                    ]" :key="item.href" :href="item.href"
                        class="flex items-center space-x-4 py-3 px-6 rounded-md transition-all"
                        :class="isActive(item.href) ? 'bg-red-600 font-bold shadow-md' : 'hover:bg-red-700'">
                        <i :class="['fa w-6', item.icon]" aria-hidden="true"></i>
                        <span>{{ item.name }}</span>
                    </Link>
                </nav>
            </aside>

            <main class="flex-1 overflow-y-auto bg-slate-50 p-8 md:p-12">
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>