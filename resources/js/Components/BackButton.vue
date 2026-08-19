<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
    fallback: {
        type: String,
        default: '/',
    },
})

const goBack = () => {
    if (window.history.length > 1) {
        window.history.back()
        return
    }

    router.visit(props.fallback)
}
</script>

<template>
    <button
        type="button"
        @click="goBack"
        class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-100"
        aria-label="Kembali ke halaman sebelumnya"
    >
        <i class="fas fa-arrow-left" aria-hidden="true"></i>
        <span>Kembali</span>
    </button>
</template>
