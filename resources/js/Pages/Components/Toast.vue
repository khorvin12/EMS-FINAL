<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const toasts = ref([])

const addToast = (message, type = 'success') => {
    const id = Date.now()
    toasts.value.push({ id, message, type })
    setTimeout(() => {
        toasts.value = toasts.value.filter(t => t.id !== id)
    }, 3000)
}

const removeToast = (id) => {
    toasts.value = toasts.value.filter(t => t.id !== id)
}

// Watch for Laravel flash messages
watch(() => page.props.flash, (flash) => {
    if (flash?.success) addToast(flash.success, 'success')
    if (flash?.error)   addToast(flash.error, 'error')
    if (flash?.warning) addToast(flash.warning, 'warning')
}, { deep: true })

// Expose so parent can trigger toasts manually
defineExpose({ addToast })
</script>

<template>
    <div class="fixed top-6 right-6 z-50 flex flex-col gap-3 w-80">
        <transition-group name="toast">
            <div v-for="toast in toasts" :key="toast.id"
                class="flex items-start gap-3 px-4 py-3 rounded-lg shadow-lg text-white text-sm font-medium cursor-pointer"
                :class="{
                    'bg-green-500': toast.type === 'success',
                    'bg-red-500':   toast.type === 'error',
                    'bg-yellow-500': toast.type === 'warning',
                    'bg-blue-500':  toast.type === 'info',
                }"
                @click="removeToast(toast.id)">
                <i class="fa-solid mt-0.5 shrink-0"
                    :class="{
                        'fa-circle-check':      toast.type === 'success',
                        'fa-circle-xmark':      toast.type === 'error',
                        'fa-triangle-exclamation': toast.type === 'warning',
                        'fa-circle-info':       toast.type === 'info',
                    }">
                </i>
                <span>{{ toast.message }}</span>
            </div>
        </transition-group>
    </div>
</template>

<style scoped>
.toast-enter-active { transition: all 0.3s ease; }
.toast-leave-active { transition: all 0.3s ease; }
.toast-enter-from   { opacity: 0; transform: translateX(100%); }
.toast-leave-to     { opacity: 0; transform: translateX(100%); }
</style>