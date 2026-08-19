<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    stats: Object,
    topSeller: Object,
    topProduct: Object,
    latestOrders: Array,
    salesChart: Array,
    totalCommission: [Number, String],
})

const sortedLatestOrders = computed(() => {
    return [...(props.latestOrders || [])].sort((a, b) => a.id - b.id)
})

const currency = (value) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value || 0))

const statusClass = (status = '') => {
    const normalized = String(status).toLowerCase()

    if (normalized.includes('selesai') || normalized.includes('completed'))
        return 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200'

    if (normalized.includes('diproses') || normalized.includes('processing'))
        return 'bg-blue-100 text-blue-700 ring-1 ring-blue-200'

    if (normalized.includes('pending') || normalized.includes('menunggu'))
        return 'bg-amber-100 text-amber-700 ring-1 ring-amber-200'

    if (normalized.includes('batal') || normalized.includes('cancel'))
        return 'bg-red-100 text-red-700 ring-1 ring-red-200'

    return 'bg-slate-100 text-slate-700 ring-1 ring-slate-200'
}

const chartMax = computed(() => {
    const values = (props.salesChart || []).map((item) => Number(item.total || 0))
    return values.length ? Math.max(...values, 1) : 1
})
</script>

<template>
    <Head title="Statistik Admin" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#0c7c43]">Admin</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Statistik</h2>
                </div>
                <Link :href="route('admin.dashboard')" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:border-slate-300">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </Link>
            </div>
        </template>

        <div class="mx-auto max-w-7xl py-8">
            <section class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-[#0b2617] via-[#0c7c43] to-[#0c7c43] p-6 text-white shadow-2xl shadow-green-900/25 md:p-8" data-aos="fade-up">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-green-100/80">Analisis bisnis</p>
                        <h3 class="mt-3 text-3xl font-black tracking-tight md:text-4xl">Statistik Lumira</h3>
                    </div>
                    <div class="grid w-full max-w-md grid-cols-2 gap-3 md:grid-cols-3">
                        <div class="rounded-2xl bg-white/10 p-3 ring-1 ring-white/10 backdrop-blur-sm">
                            <p class="text-xs text-green-100/80">User</p>
                            <p class="mt-2 text-2xl font-black">{{ props.stats?.users ?? 0 }}</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-3 ring-1 ring-white/10 backdrop-blur-sm">
                            <p class="text-xs text-green-100/80">Seller</p>
                            <p class="mt-2 text-2xl font-black">{{ props.stats?.sellers ?? 0 }}</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-3 ring-1 ring-white/10 backdrop-blur-sm md:col-span-1 col-span-2">
                            <p class="text-xs text-green-100/80">Pendapatan</p>
                            <p class="mt-2 text-xl font-black">{{ currency(props.stats?.revenue) }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Produk</p>
                            <p class="mt-3 text-3xl font-black text-slate-900">{{ props.stats?.products ?? 0 }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-sky-600">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Order</p>
                            <p class="mt-3 text-3xl font-black text-slate-900">{{ props.stats?.orders ?? 0 }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-100 text-violet-600">
                            <i class="fas fa-receipt"></i>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Komisi</p>
                            <p class="mt-3 text-2xl font-black text-slate-900">{{ currency(props.totalCommission) }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-lime-100 text-lime-700">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Top Up Pending</p>
                            <p class="mt-3 text-3xl font-black text-slate-900">{{ props.stats?.pendingTopups ?? 0 }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-100 text-yellow-600">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-slate-100" data-aos="fade-right">
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.22em] text-[#0c7c43]">Performa</p>
                            <h3 class="mt-2 text-2xl font-black text-slate-900">Top Seller</h3>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#edf9ee] text-[#0c7c43]">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>

                    <div v-if="topSeller" class="rounded-[1.5rem] bg-slate-50 p-5 ring-1 ring-slate-200">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#0c7c43] to-[#0b2617] text-xl font-black text-white">
                                {{ topSeller.name?.charAt(0)?.toUpperCase() || 'S' }}
                            </div>
                            <div>
                                <p class="text-xl font-black text-slate-900">{{ topSeller.name }}</p>
                                <p class="text-sm text-slate-500">Seller unggulan</p>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-200">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total Sales</p>
                                <p class="mt-2 text-xl font-black text-slate-900">{{ topSeller.total_sales }}</p>
                            </div>
                            <div class="rounded-2xl bg-white p-4 ring-1 ring-slate-200">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Status</p>
                                <p class="mt-2 text-xl font-black text-emerald-600">Aktif</p>
                            </div>
                        </div>
                    </div>

                    <div v-else class="mt-4 rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-slate-500">
                        Belum ada data seller.
                    </div>
                </div>

                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-slate-100" data-aos="fade-left">
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.22em] text-[#0c7c43]">Produk</p>
                            <h3 class="mt-2 text-2xl font-black text-slate-900">Produk Terlaris</h3>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#edf9ee] text-[#0c7c43]">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>

                    <div v-if="topProduct" class="rounded-[1.5rem] bg-slate-50 p-5 ring-1 ring-slate-200">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#f59e0b] to-[#f97316] text-xl font-black text-white">
                                {{ topProduct.name?.charAt(0)?.toUpperCase() || 'P' }}
                            </div>
                            <div>
                                <p class="text-xl font-black text-slate-900">{{ topProduct.name }}</p>
                                <p class="text-sm text-slate-500">Terjual {{ topProduct.sold_count || 0 }} unit</p>
                            </div>
                        </div>
                    </div>

                    <div v-else class="mt-4 rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-slate-500">
                        Belum ada produk terjual.
                    </div>
                </div>
            </section>

            <section class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-slate-100" data-aos="fade-up">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.22em] text-[#0c7c43]">Penjualan</p>
                        <h3 class="mt-2 text-2xl font-black text-slate-900">Trend Pendapatan</h3>
                    </div>
                </div>

                <div class="flex h-52 items-end gap-3 overflow-x-auto pb-2">
                    <div v-for="(item, index) in salesChart" :key="index" class="flex min-w-[52px] flex-1 flex-col items-center justify-end gap-2">
                        <div class="w-full rounded-t-2xl bg-gradient-to-t from-[#0c7c43] to-[#5ccf8e]" :style="{ height: `${(Number(item.total || 0) / chartMax) * 100}%` }"></div>
                        <span class="text-xs font-bold text-slate-500">{{ new Date(2026, Number(item.month) - 1, 1).toLocaleString('id-ID', { month: 'short' }) }}</span>
                    </div>
                    <div v-if="!salesChart || salesChart.length === 0" class="w-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-center text-slate-500">
                        Belum ada data penjualan.
                    </div>
                </div>
            </section>

            <section class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-slate-100" data-aos="fade-up">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.22em] text-[#0c7c43]">Transaksi</p>
                        <h3 class="mt-2 text-2xl font-black text-slate-900">Order Terbaru</h3>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-200 text-sm uppercase tracking-[0.14em] text-slate-400">
                                <th class="py-3 pr-4 font-semibold">Order</th>
                                <th class="py-3 pr-4 font-semibold">User</th>
                                <th class="py-3 pr-4 font-semibold">Total</th>
                                <th class="py-3 pr-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in sortedLatestOrders" :key="order.id" class="border-b border-slate-100 text-sm text-slate-700 last:border-0">
                                <td class="py-4 pr-4 font-semibold text-slate-900">#{{ order.id }}</td>
                                <td class="py-4 pr-4">{{ order.user?.name ?? '-' }}</td>
                                <td class="py-4 pr-4 font-bold text-slate-900">{{ currency(order.total_price) }}</td>
                                <td class="py-4 pr-4">
                                    <span :class="statusClass(order.status)" class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]">
                                        {{ order.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
