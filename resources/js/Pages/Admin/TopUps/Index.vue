<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    topUps: Array,
})

const sortedTopUps = computed(() => {
    return [...props.topUps].sort((a, b) => a.id - b.id)
})

const approve = (id) => {
    router.post(route('admin.topups.approve', id))
}

const reject = (id) => {
    router.post(route('admin.topups.reject', id))
}

const destroy = (id) => {
    if (!confirm('Hapus riwayat top up ini?')) {
        return
    }

    router.delete(route('admin.topups.destroy', id))
}

const currency = (value) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value || 0))

const proofImageUrl = (path) => {
    if (!path) return ''
    return path.startsWith('http') ? path : `/storage/${path}`
}

const statusClass = (status = '') => {
    const normalized = status.toLowerCase()

    if (normalized.includes('approved') || normalized.includes('disetujui')) {
        return 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200'
    }

    if (normalized.includes('rejected') || normalized.includes('ditolak')) {
        return 'bg-red-100 text-red-700 ring-1 ring-red-200'
    }

    return 'bg-amber-100 text-amber-700 ring-1 ring-amber-200'
}
</script>

<template>
    <Head title="Manajemen Top Up" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#0c7c43]">Finance</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Manajemen Top Up</h2>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-7xl py-8">
            <section class="grid gap-4 md:grid-cols-3" data-aos="fade-up">
                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                    <p class="text-sm text-slate-500">Total Request</p>
                    <p class="mt-3 text-3xl font-black text-slate-900">{{ topUps?.length ?? 0 }}</p>
                </div>
                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                    <p class="text-sm text-slate-500">Pending</p>
                    <p class="mt-3 text-3xl font-black text-slate-900">{{ topUps?.filter((t) => String(t.status).toLowerCase() === 'pending').length ?? 0 }}</p>
                </div>
                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                    <p class="text-sm text-slate-500">Total Nominal</p>
                    <p class="mt-3 text-2xl font-black text-slate-900">{{ currency(topUps?.reduce((sum, item) => sum + Number(item.amount || 0), 0)) }}</p>
                </div>
            </section>

            <section class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white/75 p-6 shadow-[0_18px_50px_rgba(15,23,42,0.04)] ring-1 ring-slate-100 backdrop-blur-md" data-aos="fade-up">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.22em] text-[#0c7c43]">Transaksi</p>
                        <h3 class="mt-2 text-2xl font-black text-slate-900">Daftar Top Up</h3>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#edf9ee] text-[#0c7c43]">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-200 text-sm uppercase tracking-[0.14em] text-slate-400">
                                <th class="py-3 pr-4 font-semibold">User</th>
                                <th class="py-3 pr-4 font-semibold">Nominal</th>
                                <th class="py-3 pr-4 font-semibold">Bukti TF</th>
                                <th class="py-3 pr-4 font-semibold">Status</th>
                                <th class="py-3 pr-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="topup in sortedTopUps" :key="topup.id" class="border-b border-slate-100 text-sm text-slate-700 last:border-0">
                                <td class="py-4 pr-4 font-semibold text-slate-900">{{ topup.user?.name ?? '-' }}</td>
                                <td class="py-4 pr-4 font-bold text-slate-900">{{ currency(topup.amount) }}</td>
                                <td class="py-4 pr-4">
                                    <a v-if="proofImageUrl(topup.proof)" :href="proofImageUrl(topup.proof)" target="_blank" rel="noopener noreferrer" class="inline-block">
                                        <img :src="proofImageUrl(topup.proof)" :alt="`Bukti transfer ${topup.user?.name ?? 'user'}`" class="h-20 w-20 rounded-xl object-cover ring-1 ring-slate-200 shadow-sm transition hover:scale-[1.02]" />
                                    </a>
                                    <span v-else class="text-xs font-medium text-slate-400">Belum ada</span>
                                </td>
                                <td class="py-4 pr-4">
                                    <span :class="statusClass(topup.status)" class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]">
                                        {{ topup.status }}
                                    </span>
                                </td>
                                <td class="py-4 pr-4">
                                    <div v-if="String(topup.status).toLowerCase() === 'pending'" class="flex gap-2">
                                        <button @click="approve(topup.id)" class="rounded-xl bg-[#0c7c43] px-3.5 py-2 text-xs font-bold text-white shadow-lg shadow-green-900/15 transition hover:-translate-y-0.5 hover:bg-[#0b2617]">
                                            <i class="fas fa-check mr-1"></i>Approve
                                        </button>
                                        <button @click="reject(topup.id)" class="rounded-xl bg-red-600 px-3.5 py-2 text-xs font-bold text-white shadow-lg shadow-red-900/15 transition hover:-translate-y-0.5 hover:bg-red-700">
                                            <i class="fas fa-times mr-1"></i>Reject
                                        </button>
                                    </div>
                                    <div v-else class="flex gap-2">
                                        <button @click="destroy(topup.id)" class="rounded-xl bg-slate-800 px-3.5 py-2 text-xs font-bold text-white shadow-lg shadow-slate-900/15 transition hover:-translate-y-0.5 hover:bg-slate-900">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>