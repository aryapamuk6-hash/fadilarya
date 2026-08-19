
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    sellers: Array,
})

const sortedSellers = computed(() => {
    return [...props.sellers].sort((a, b) => a.id - b.id)
})

function warningSeller(id) {
    if (confirm('Berikan warning kepada seller ini?')) {
        router.post(route('admin.sellers.warning', id))
    }
}

function suspendSeller(id) {
    if (confirm('Suspend seller ini?')) {
        router.post(route('admin.sellers.suspend', id))
    }
}

function unsuspendSeller(id) {
    if (confirm('Aktifkan kembali seller ini?')) {
        router.post(route('admin.sellers.unsuspend', id))
    }
}

const getInitials = (name = '') => {
    const parts = name.trim().split(/\s+/).filter(Boolean)
    if (!parts.length) return 'S'
    return parts.slice(0, 2).map((part) => part[0]?.toUpperCase() || '').join('')
}

const statusClass = (isSuspended) =>
    isSuspended ? 'bg-red-100 text-red-700 ring-1 ring-red-200' : 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200'
</script>

<template>
    <Head title="Kelola Seller" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#0c7c43]">Marketplace</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Kelola Seller</h2>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-7xl py-8">
            <section class="grid gap-4 md:grid-cols-3" data-aos="fade-up">
                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-slate-500">Total Seller</p>
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#edf9ee] text-[#0c7c43]"><i class="fas fa-users"></i></div>
                    </div>
                    <p class="mt-4 text-3xl font-black text-slate-900">{{ sellers?.length ?? 0 }}</p>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-slate-500">Warning</p>
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-600"><i class="fas fa-exclamation-triangle"></i></div>
                    </div>
                    <p class="mt-4 text-3xl font-black text-slate-900">{{ sellers?.reduce((sum, seller) => sum + Number(seller.warning_count || 0), 0) }}</p>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-slate-500">Suspended</p>
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-red-100 text-red-600"><i class="fas fa-ban"></i></div>
                    </div>
                    <p class="mt-4 text-3xl font-black text-slate-900">{{ sellers?.filter((seller) => seller.is_suspended).length ?? 0 }}</p>
                </div>
            </section>

            <section class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-slate-100" data-aos="fade-up" data-aos-delay="100">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.22em] text-[#0c7c43]">Seller</p>
                        <h3 class="mt-2 text-2xl font-black text-slate-900">Daftar Seller</h3>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#edf9ee] text-[#0c7c43]">
                        <i class="fas fa-store"></i>
                    </div>
                </div>

                <div v-if="!sellers?.length" class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-slate-500">
                    Belum ada seller.
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                        <thead class="bg-slate-50 text-xs font-black uppercase tracking-[0.2em] text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Seller</th>
                                <th class="px-4 py-3">Toko</th>
                                <th class="px-4 py-3">Warning</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="seller in sortedSellers" :key="seller.id" class="hover:bg-slate-50">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#edf9ee] text-sm font-black text-[#0c7c43]">
                                            {{ getInitials(seller.name) }}
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-900">{{ seller.name }}</div>
                                            <div class="text-xs text-slate-500">{{ seller.email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">{{ seller.store?.name ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700">{{ seller.warning_count || 0 }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span :class="statusClass(seller.is_suspended)" class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold">
                                        {{ seller.is_suspended ? 'Suspended' : 'Active' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <button @click="warningSeller(seller.id)" class="rounded-xl bg-amber-500 px-3 py-2 text-xs font-bold text-white transition hover:bg-amber-600">Warning</button>
                                        <button v-if="!seller.is_suspended" @click="suspendSeller(seller.id)" class="rounded-xl bg-red-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-red-700">Suspend</button>
                                        <button v-else @click="unsuspendSeller(seller.id)" class="rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-emerald-700">Unsuspend</button>
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
