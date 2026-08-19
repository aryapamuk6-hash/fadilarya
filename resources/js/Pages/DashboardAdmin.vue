<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Panel kontrol</p>
                    <h2 class="mt-2 text-2xl font-black text-[#0b2617] md:text-3xl">
                        Dashboard Admin
                    </h2>
                </div>
                <div class="rounded-full bg-white px-4 py-2 text-sm font-bold text-[#0c7c43] shadow-sm ring-1 ring-green-100">
                    Kelola ekosistem Lumira
                </div>
            </div>
        </template>

        <div class="min-h-screen overflow-x-hidden bg-[#f6f8f6] py-8">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="rounded-[2rem] bg-gradient-to-br from-[#edf9ee] via-white to-[#f3faf5] p-8 shadow-[0_20px_60px_rgba(18,41,24,0.08)] ring-1 ring-green-100" data-aos="fade-up">
                    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div data-aos="fade-right">
                            <p class="text-sm font-black uppercase tracking-[0.2em] text-[#0c7c43]">Admin panel</p>
                            <h1 class="mt-2 text-3xl font-black text-[#0b2617] md:text-4xl">
                                Admin Panel Lumira
                            </h1>
                        </div>
                        <div class="rounded-full bg-[#0c7c43] px-4 py-2 text-sm font-bold text-white shadow-lg shadow-green-900/20" data-aos="fade-left">
                            <i class="fas fa-check-circle mr-2"></i>Operasional terkontrol
                        </div>
                    </div>

                    <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <Link :href="route('admin.seller.applications')" class="rounded-[1.6rem] bg-gradient-to-br from-[#0c7c43] to-[#0b2617] p-6 text-white shadow-lg shadow-green-900/20 transition duration-300 hover:-translate-y-1 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-2xl"><i class="fas fa-file-alt"></i></div>
                            <h3 class="mt-6 text-xl font-black">Pengajuan Seller</h3>
                            <p class="mt-2 text-sm leading-6 text-green-50/80">Kelola pengajuan seller terbaru.</p>
                        </Link>

                        <Link :href="route('admin.products.index')" class="rounded-[1.6rem] bg-white p-6 text-slate-800 ring-1 ring-slate-200 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-green-900/10" data-aos="fade-up" data-aos-delay="50">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-slate-500"><i class="fas fa-box"></i></div>
                            <h3 class="mt-6 text-xl font-black">Produk</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Pantau katalog dan produk aktif.</p>
                        </Link>

                        <Link :href="route('admin.sellers.index')" class="rounded-[1.6rem] bg-white p-6 text-slate-800 ring-1 ring-slate-200 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-green-900/10" data-aos="fade-up" data-aos-delay="100">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-slate-500"><i class="fas fa-users"></i></div>
                            <h3 class="mt-6 text-xl font-black">Seller</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Kelola mitra dan status toko.</p>
                        </Link>

                        <Link :href="route('admin.topups.index')" class="rounded-[1.6rem] bg-white p-6 text-slate-800 ring-1 ring-slate-200 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-green-900/10" data-aos="fade-up" data-aos-delay="150">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-slate-500"><i class="fas fa-credit-card"></i></div>
                            <h3 class="mt-6 text-xl font-black">Top Up</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Pantau saldo dan transaksi pengguna.</p>
                        </Link>

                        <Link :href="route('admin.banners.index')" class="rounded-[1.6rem] bg-white p-6 text-slate-800 ring-1 ring-slate-200 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-green-900/10" data-aos="fade-up" data-aos-delay="200">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-slate-500"><i class="fas fa-images"></i></div>
                            <h3 class="mt-6 text-xl font-black">Banner</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Kelola banner promosi yang tampil di user.</p>
                        </Link>

                        <Link :href="route('admin.withdrawals.index')" class="rounded-[1.6rem] bg-white p-6 text-slate-800 ring-1 ring-slate-200 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-green-900/10" data-aos="fade-up" data-aos-delay="250">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-slate-500"><i class="fas fa-money-bill-transfer"></i></div>
                            <h3 class="mt-6 text-xl font-black">Tarik Dana</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Review pengajuan penarikan dana seller.</p>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
