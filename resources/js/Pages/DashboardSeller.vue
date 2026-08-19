
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    announcement: Object,
    stats: Object,
    topProduct: Object,
    store: Object,
    pendingOrders: Number,
    completedOrders: Number,
    currentMonthRevenue: Number,
})

const formatCurrency = (value) => {
    return Number(value || 0).toLocaleString('id-ID')
}
</script>

<template>
    <Head title="Dashboard Seller" />

    <AuthenticatedLayout>
        <div v-if="announcement" class="mx-auto mt-6 max-w-7xl px-6 lg:px-8" data-aos="fade-down">
            <div class="rounded-2xl border border-amber-200 bg-gradient-to-r from-amber-50 to-yellow-50 p-4 shadow-sm shadow-amber-200/40">
                <h3 class="text-lg font-black text-[#0b2617]">
                    <i class="fas fa-bullhorn mr-2 text-slate-500"></i>{{ announcement.title }}
                </h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    {{ announcement.content }}
                </p>
            </div>
        </div>

        <template #header>
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Welcome seller</p>
                    <h2 class="mt-2 text-2xl font-black text-[#0b2617] md:text-3xl">
                        Halo, {{ $page.props.auth.user.name }}
                    </h2>
                </div>
                <div class="flex flex-wrap items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-bold text-[#0c7c43] shadow-sm ring-1 ring-green-100">
                    <i class="fas fa-store text-[#0c7c43]"></i>
                    Kelola produk & pesanan toko Anda
                </div>
            </div>
        </template>

        <div class="min-h-screen overflow-x-hidden bg-[#f6f8f6] mx-auto py-8">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mb-8 rounded-[2rem] bg-gradient-to-br from-[#edf9ee] via-white to-[#f3faf5] p-8 shadow-[0_20px_60px_rgba(18,41,24,0.08)] ring-1 ring-green-100" data-aos="fade-up">
                    <p class="text-sm font-black uppercase tracking-[0.2em] text-[#0c7c43]">Seller dashboard</p>
                    <h1 class="mt-3 text-3xl font-black text-[#0b2617] md:text-4xl">
                        Selamat datang di toko Anda
                    </h1>
                    <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                        Kelola toko, produk, dan pesanan pelanggan Anda melalui dashboard yang dibuat agar lebih cepat dan profesional.
                    </p>
                </div>

                <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <Link :href="route('seller.products.index')" class="group rounded-[1.6rem] bg-gradient-to-br from-[#0c7c43] to-[#0b2617] p-6 text-white shadow-lg shadow-green-900/20 transition duration-300 hover:-translate-y-1 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="0">
                        <div class="flex items-center justify-between">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-xl"><i class="fas fa-box"></i></span>
                            <span class="rounded-full bg-white/10 px-2 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-green-100">Products</span>
                        </div>
                        <h3 class="mt-8 text-xl font-black">Produk Saya</h3>
                        <p class="mt-2 text-sm leading-6 text-green-50/80">Kelola produk yang Anda jual dengan lebih rapi dan cepat.</p>
                    </Link>

                    <Link :href="route('seller.products.create')" class="group rounded-[1.6rem] bg-gradient-to-br from-[#e9f7ed] to-[#f3faf5] p-6 text-slate-800 ring-1 ring-green-100 transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-green-900/10" data-aos="fade-up" data-aos-delay="50">
                        <div class="flex items-center justify-between">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-xl text-[#0c7c43]"><i class="fas fa-plus-circle"></i></span>
                            <span class="rounded-full bg-white px-2 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#0c7c43]">New</span>
                        </div>
                        <h3 class="mt-8 text-xl font-black">Tambah Produk</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Tambahkan produk baru ke marketplace dengan lebih gampang.</p>
                    </Link>

                    <Link :href="route('seller.orders.index')" class="group rounded-[1.6rem] bg-gradient-to-br from-[#f4faf4] to-[#edf7ee] p-6 text-slate-800 ring-1 ring-green-100 transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-green-900/10" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center justify-between">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-xl text-[#0c7c43]"><i class="fas fa-shopping-basket"></i></span>
                            <span class="rounded-full bg-[#eafaf1] px-2 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#0c7c43]">Orders</span>
                        </div>
                        <h3 class="mt-8 text-xl font-black">Pesanan Masuk</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Pantau dan proses pesanan pelanggan dengan cepat.</p>
                    </Link>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <Link :href="route('marketplace')" class="group rounded-[1.6rem] bg-gradient-to-br from-[#f9faf9] to-[#edf8ee] p-6 text-slate-800 ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-green-900/10" data-aos="fade-up" data-aos-delay="150">
                        <div class="flex items-center justify-between">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-xl text-slate-500"><i class="fas fa-store"></i></span>
                            <span class="rounded-full bg-white px-2 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-slate-500">View</span>
                        </div>
                        <h3 class="mt-8 text-xl font-black">Marketplace</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Lihat produk terbaru di platform dan cari peluang lebih besar.</p>
                    </Link>

                    <Link :href="route('chat.index')" class="group rounded-[1.6rem] bg-gradient-to-br from-[#fefce8] to-[#fff6d4] p-6 text-slate-800 ring-1 ring-yellow-100 transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-green-900/10" data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center justify-between">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-xl text-[#0c7c43]"><i class="fas fa-comments"></i></span>
                            <span class="rounded-full bg-white px-2 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#0c7c43]">Inbox</span>
                        </div>
                        <h3 class="mt-8 text-xl font-black">Pesan Masuk</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Lihat chat dari pembeli dan balas pesan mereka langsung.</p>
                    </Link>

                    <Link :href="route('seller.withdrawals.index')" class="group rounded-[1.6rem] bg-gradient-to-br from-[#ecfdf5] to-[#dcfce7] p-6 text-slate-800 ring-1 ring-emerald-100 transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-green-900/10" data-aos="fade-up" data-aos-delay="250">
                        <div class="flex items-center justify-between">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-xl text-[#0c7c43]"><i class="fas fa-money-bill-wave"></i></span>
                            <span class="rounded-full bg-white px-2 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#0c7c43]">Withdraw</span>
                        </div>
                        <h3 class="mt-8 text-xl font-black">Tarik Dana</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Ajukan penarikan saldo dan cek riwayat withdraw Anda.</p>
                    </Link>

                    <Link :href="route('profile.edit')" class="group rounded-[1.6rem] bg-gradient-to-br from-[#0b2617] to-[#163d2a] p-6 text-white shadow-lg shadow-green-900/20 transition duration-300 hover:-translate-y-1 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="300">
                        <div class="flex items-center justify-between">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-xl"><i class="fas fa-user-circle"></i></span>
                            <span class="rounded-full bg-white/10 px-2 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-green-100">Profile</span>
                        </div>
                        <h3 class="mt-8 text-xl font-black">Profil Saya</h3>
                        <p class="mt-2 text-sm leading-6 text-green-50/80">Edit profil akun toko Anda dan kelola data bisnis.</p>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

