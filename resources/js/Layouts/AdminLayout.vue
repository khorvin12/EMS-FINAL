<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import Toast from '@/Pages/Components/Toast.vue'

const page = usePage()
const isLoggingOut = ref(false)
const sidebarOpen = ref(false)
const isLoading = ref(false)
const showContent = ref(true)

const user = computed(() => page.props.auth?.user)
const isActive = (path) => page.url.startsWith(path)

const handleLogout = () => {
    isLoggingOut.value = true
    router.post('/logout')
}

router.on('start', () => { isLoading.value = true; showContent.value = false })
router.on('finish', () => { isLoading.value = false; showContent.value = true })

const navItems = [
    { name: 'Dashboard',   href: '/dashboard',          icon: 'fa-gauge' },
    { name: 'Employees',   href: '/manageemployees',     icon: 'fa-users' },
    { name: 'Departments', href: '/departments',         icon: 'fa-building' },
    { name: 'Leaves',      href: '/manageleaves/leaves', icon: 'fa-calendar-xmark' },
    { name: 'Settings',    href: '/settings',            icon: 'fa-gear' },
]
</script>

<template>
    <div class="flex flex-col h-screen overflow-hidden bg-slate-50">

        <Toast />

        <!-- Loading Bar -->
        <div v-if="isLoading" class="fixed top-0 left-0 w-full z-50">
            <div class="h-1 bg-red-300 animate-pulse">
                <div class="h-1 bg-red-600" style="width: 70%"></div>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div v-if="isLoading" class="fixed inset-0 z-40 bg-black/10 flex items-center justify-center pointer-events-none">
            <div class="bg-white rounded-full p-4 shadow-lg">
                <i class="fa-solid fa-spinner fa-spin text-red-600 text-2xl"></i>
            </div>
        </div>

        <!-- Top Header -->
        <header class="bg-red-700 text-white shadow-md z-30 flex-shrink-0">
            <nav class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="md:hidden p-2 rounded-lg hover:bg-red-600 transition" aria-label="Toggle menu">
                        <i :class="sidebarOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-lg"></i>
                    </button>
                    <div class="flex items-center gap-2">
                        <div class="bg-white/20 rounded-lg p-1.5">
                            <i class="fa-solid fa-shield-halved text-white text-sm"></i>
                        </div>
                        <span class="text-lg font-bold tracking-wide hidden sm:block">Administrator</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2 bg-white/10 rounded-lg px-3 py-1.5">
                        <div class="w-7 h-7 bg-white/30 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-user text-xs"></i>
                        </div>
                        <span class="text-sm font-medium">{{ user ? `${user.first_name} ${user.last_name}` : 'Admin' }}</span>
                    </div>
                    <button @click="handleLogout" :disabled="isLoggingOut"
                        class="bg-red-800 hover:bg-red-900 px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2 disabled:opacity-60">
                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                        <span>{{ isLoggingOut ? 'Logging out…' : 'Logout' }}</span>
                    </button>
                </div>
            </nav>
        </header>

        <div class="flex flex-1 overflow-hidden relative">

            <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/40 z-20 md:hidden"></div>

            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
                class="fixed md:static z-20 h-full bg-gray-900 text-white w-64 flex-shrink-0 flex flex-col transition-transform duration-300 ease-in-out">
                <div class="px-6 py-5 border-b border-white/10">
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">EMS Portal</p>
                    <p class="text-sm font-medium text-white mt-0.5">Admin Panel</p>
                </div>
                <nav class="flex-1 py-6 space-y-1 px-3 overflow-y-auto">
                    <Link v-for="item in navItems" :key="item.href" :href="item.href" @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-150"
                        :class="isActive(item.href)
                            ? 'bg-red-600 text-white shadow-md shadow-red-900/40'
                            : 'text-gray-300 hover:bg-white/8 hover:text-white'">
                        <i :class="['fa-solid fa-fw', item.icon]" class="w-4 text-center"></i>
                        <span>{{ item.name }}</span>
                    </Link>
                </nav>
                <div class="px-6 py-4 border-t border-white/10">
                    <p class="text-xs text-gray-500">Employee Management System</p>
                </div>
            </aside>

            <main class="flex-1 overflow-y-auto p-6 md:p-10">
                <Transition name="fade" mode="out-in">
                    <div :key="$page.url">
                        <slot />
                    </div>
                </Transition>
            </main>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active { transition: opacity 0.2s ease, transform 0.2s ease; }
.fade-leave-active { transition: opacity 0.15s ease, transform 0.15s ease; }
.fade-enter-from   { opacity: 0; transform: translateY(8px); }
.fade-leave-to     { opacity: 0; transform: translateY(-8px); }
</style>