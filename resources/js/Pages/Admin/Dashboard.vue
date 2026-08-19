<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    stats: Object,
})

const quickActions = [
    { label: 'Kelola Pengajuan Seller', href: route('admin.seller.applications'), color: 'bg-[#f3f4f6]', icon: 'fa-file-alt' },
    { label: 'Kelola Seller', href: route('admin.sellers.index'), color: 'bg-[#eef2f7]', icon: 'fa-users' },
    { label: 'Kelola Top Up', href: route('admin.topups.index'), color: 'bg-[#f5f5f4]', icon: 'fa-wallet' },
    { label: 'Kelola Banner', href: route('admin.banners.index'), color: 'bg-[#eefcf3]', icon: 'fa-images' },
    { label: 'Tarik Dana', href: route('admin.withdrawals.index'), color: 'bg-[#f5f7ef]', icon: 'fa-money-bill-transfer' },
    { label: 'Kelola Kategori', href: route('admin.categories.index'), color: 'bg-[#f1f5f9]', icon: 'fa-tags' },
    { label: 'Kelola Pengumuman', href: route('admin.announcements.index'), color: 'bg-[#f8fafc]', icon: 'fa-bullhorn' },
    { label: 'Kelola Produk', href: route('admin.products.index'), color: 'bg-[#f3f6f3]', icon: 'fa-boxes' },
    { label: 'Kelola Voucher', href: route('admin.vouchers.index'), color: 'bg-[#fff7ed]', icon: 'fa-ticket-alt' },
]

const currency = (value) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value || 0))

</script>

<template>
    <Head title="Dashboard Admin" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#0c7c43]">Admin</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">
                        Dashboard Admin
                    </h2>
                </div>
                <Link :href="route('admin.statistics')" class="inline-flex items-center gap-2 rounded-full bg-[#0c7c43] px-4 py-2 text-sm font-bold text-white shadow-lg shadow-green-900/20 transition hover:-translate-y-0.5">
                    <i class="fas fa-chart-line"></i>
                    Statistik
                </Link>
            </div>
        </template>

        <div class="mx-auto max-w-7xl py-8">
            <section class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-[#0b2617] via-[#0c7c43] to-[#0c7c43] p-6 text-white shadow-2xl shadow-green-900/25 md:p-8" data-aos="fade-up">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-green-100/80">Ringkasan Operasional</p>
                        <h3 class="mt-3 text-3xl font-black tracking-tight md:text-4xl">Marketplace Lumira sedang berjalan optimal</h3>
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

            <section class="mt-8">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-xl font-black tracking-tight text-slate-900">Menu Cepat</h3>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <Link
                        v-for="action in quickActions"
                        :key="action.label"
                        :href="action.href"
                        class="group relative overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white p-4 text-slate-800 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-slate-300 hover:shadow-md"
                        :class="action.color"
                        data-aos="fade-up"
                    >
                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-slate-100"></div>
                        <div class="relative flex items-center justify-between gap-4">
                            <div>
                                <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-lg text-slate-600 ring-1 ring-slate-200">
                                    <i :class="['fas', action.icon]" aria-hidden="true"></i>
                                </div>
                                <p class="text-base font-bold leading-6 text-slate-800">{{ action.label }}</p>
                            </div>
                            <span class="text-2xl text-slate-500 transition group-hover:translate-x-1">→</span>
                        </div>
                    </Link>
                </div>
            </section>

            <section class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100" data-aos="fade-up" data-aos-delay="50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total User</p>
                            <p class="mt-3 text-3xl font-black text-slate-900">{{ props.stats?.users ?? 0 }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-[#0c7c43]">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Seller</p>
                            <p class="mt-3 text-3xl font-black text-slate-900">{{ props.stats?.sellers ?? 0 }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-600">
                            <i class="fas fa-store"></i>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100" data-aos="fade-up" data-aos-delay="150">
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

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100" data-aos="fade-up" data-aos-delay="200">
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

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100" data-aos="fade-up" data-aos-delay="250">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Pendapatan</p>
                            <p class="mt-3 text-2xl font-black text-slate-900">{{ currency(props.stats?.revenue) }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-lime-100 text-lime-700">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100" data-aos="fade-up" data-aos-delay="300">
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

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100" data-aos="fade-up" data-aos-delay="350">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Pengajuan Seller</p>
                            <p class="mt-3 text-3xl font-black text-slate-900">{{ props.stats?.pendingSellerApplications ?? 0 }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-100 text-rose-600">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Produk Sold Out</p>
                            <p class="mt-3 text-3xl font-black text-slate-900">{{ props.stats?.soldOutProducts ?? 0 }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-red-600">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </section>


        </div>
    </AuthenticatedLayout>
</template>

