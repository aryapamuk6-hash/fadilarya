<script setup>
import { computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
    announcement: Object,
    stats: {
        type: Object,
        default: () => ({
            balance: 0,
            orders: 0,
            shippedOrders: 0,
            wishlistCount: 0,
        }),
    },
    latestProducts: {
        type: Array,
        default: () => [],
    },
    recentOrders: {
        type: Array,
        default: () => [],
    },
    bestSellingProducts: {
        type: Array,
        default: () => [],
    },
    topStores: {
        type: Array,
        default: () => [],
    },
})

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value ?? 0))
}

const statsCards = computed(() => [
    {
        label: 'Saldo',
        value: formatCurrency(props.stats.balance),
        tone: 'emerald',
        icon: 'fa-wallet',
        badgeClass: 'bg-slate-100 text-slate-500',
    },
    {
        label: 'Total Pesanan',
        value: props.stats.orders ?? 0,
        tone: 'blue',
        icon: 'fa-box',
        badgeClass: 'bg-slate-100 text-slate-500',
    },
    {
        label: 'Sedang Dikirim',
        value: props.stats.shippedOrders ?? 0,
        tone: 'amber',
        icon: 'fa-truck',
        badgeClass: 'bg-slate-100 text-slate-500',
    },
    {
        label: 'Wishlist',
        value: props.stats.wishlistCount ?? 0,
        tone: 'pink',
        icon: 'fa-heart',
        badgeClass: 'bg-slate-100 text-slate-500',
    },
])

const sortedRecentOrders = computed(() => {
    return [...props.recentOrders].sort((a, b) => b.id - a.id)
})

const sortedBestSellingProducts = computed(() => {
    return [...props.bestSellingProducts].sort((a, b) => a.id - b.id)
})

const sortedLatestProducts = computed(() => {
    return [...props.latestProducts].sort((a, b) => b.id - a.id)
})

const sortedTopStores = computed(() => {
    return [...props.topStores].sort((a, b) => a.id - b.id)
})
</script>

<template>
    <Head title="Dashboard User" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Selamat datang kembali</p>
                    <h2 class="mt-2 text-2xl font-black text-[#0b2617] md:text-3xl">
                        {{ $page.props.auth.user.name }}
                    </h2>
                </div>

                <div class="flex flex-wrap gap-2">
                    <Link :href="route('topup.index')" class="rounded-xl bg-gradient-to-r from-[#0c7c43] to-[#0b6f3f] px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-green-900/25 transition duration-200 hover:-translate-y-0.5 hover:shadow-xl">
                        <i class="fas fa-wallet mr-2"></i>Top Up Saldo
                    </Link>
                    <Link :href="route('orders.index')" class="rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition duration-200 hover:-translate-y-0.5 hover:text-[#0c7c43] hover:shadow-md">
                        <i class="fas fa-box mr-2 text-slate-500"></i>Pesanan Saya
                    </Link>
                    <Link :href="route('notifications.index')" class="rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200 transition duration-200 hover:-translate-y-0.5 hover:text-[#0c7c43] hover:shadow-md hover:ring-[#0c7c43]">
                        <i class="fas fa-bell mr-2"></i>Notifikasi
                    </Link>
                </div>
            </div>
        </template>

        <!-- Announcement Banner -->
        <div v-if="announcement" class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8" data-aos="fade-down">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-400 via-orange-400 to-red-400 p-6 shadow-lg shadow-orange-900/20">
                <div class="absolute -right-20 -top-20 h-40 w-40 rounded-full bg-white/10 blur-3xl"></div>
                <div class="relative">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                            <i class="fas fa-megaphone text-xl text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-black text-white">
                                {{ announcement.title }}
                            </h3>
                            <p class="mt-2 text-sm leading-6 text-white/90">
                                {{ announcement.content }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-auto max-w-7xl py-8 px-4 sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div v-for="(stat, index) in statsCards" :key="index" class="group rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-slate-200" data-aos="fade-up" :data-aos-delay="`${index * 75}`" data-aos-duration="600">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ stat.label }}</p>
                            <h2 class="mt-3 text-3xl font-black" :class="{ 'text-emerald-600': stat.tone === 'emerald', 'text-blue-600': stat.tone === 'blue', 'text-amber-600': stat.tone === 'amber', 'text-pink-600': stat.tone === 'pink' }">
                                {{ stat.value }}
                            </h2>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl" :class="{ 'bg-emerald-50 text-emerald-600': stat.tone === 'emerald', 'bg-blue-50 text-blue-600': stat.tone === 'blue', 'bg-amber-50 text-amber-600': stat.tone === 'amber', 'bg-pink-50 text-pink-600': stat.tone === 'pink' }">
                            <i :class="['fas', stat.icon, 'text-xl']"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error/Status Messages -->
            <div v-if="page.props.flash?.error" class="mb-6 rounded-2xl border-l-4 border-red-500 bg-red-50 p-4 shadow-sm" data-aos="fade-right">
                <p class="text-sm font-semibold text-red-700">{{ page.props.flash.error }}</p>
            </div>

            <div v-if="$page.props.auth.user.seller_status === 'suspended'" class="mb-6 rounded-2xl border-l-4 border-red-500 bg-red-50 p-5 shadow-sm" data-aos="fade-right">
                <h3 class="text-lg font-bold text-red-700">⚠️ Status Seller Ditangguhkan</h3>
                <p class="mt-2 text-sm text-red-600">
                    Hak seller Anda sedang dicabut oleh admin. Anda tetap dapat berbelanja sebagai pembeli normal.
                </p>
                <Link href="/seller/apply" class="mt-4 inline-block rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition duration-200 hover:bg-red-700">
                    Ajukan Seller Kembali
                </Link>
            </div>

            <!-- Quick Access Section -->
            <div class="mb-10" data-aos="fade-up" data-aos-delay="300">
                <div class="mb-6">
                    <h2 class="text-2xl font-black text-slate-900">Akses Cepat</h2>
                    <p class="mt-1 text-sm text-slate-600">Jelajahi fitur utama Lumira dalam satu klik</p>
                </div>

                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    <Link :href="route('marketplace')" class="group overflow-hidden rounded-2xl bg-gradient-to-br from-[#0c7c43] to-[#0a5f36] p-6 text-white shadow-lg shadow-green-900/20 transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-white/20 text-2xl group-hover:scale-110 transition duration-300">
                            <i class="fas fa-store"></i>
                        </div>
                        <h3 class="font-bold">Marketplace</h3>
                        <p class="mt-1 text-xs text-green-50">Jelajahi produk</p>
                    </Link>

                    <Link :href="route('cart.index')" class="group overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white shadow-lg shadow-blue-900/20 transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-white/20 text-2xl group-hover:scale-110 transition duration-300">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3 class="font-bold">Keranjang</h3>
                        <p class="mt-1 text-xs text-blue-50">Lihat belanja</p>
                    </Link>

                    <Link :href="route('wishlist.index')" class="group overflow-hidden rounded-2xl bg-gradient-to-br from-pink-500 to-red-500 p-6 text-white shadow-lg shadow-pink-900/20 transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-white/20 text-2xl group-hover:scale-110 transition duration-300">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="font-bold">Wishlist</h3>
                        <p class="mt-1 text-xs text-pink-50">Produk favorit</p>
                    </Link>

                    <Link v-if="$page.props.auth.user.seller_status !== 'suspended'" :href="route('seller.apply')" class="group overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 p-6 text-white shadow-lg shadow-orange-900/20 transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-white/20 text-2xl group-hover:scale-110 transition duration-300">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <h3 class="font-bold">Jual Produk</h3>
                        <p class="mt-1 text-xs text-amber-50">Daftar seller</p>
                    </Link>

                    <div v-else class="rounded-2xl bg-slate-100 p-6 text-slate-600 ring-1 ring-slate-200">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-white text-2xl text-slate-400">
                            <i class="fas fa-ban"></i>
                        </div>
                        <h3 class="font-bold">Seller</h3>
                        <p class="mt-1 text-xs">Ditangguhkan</p>
                    </div>
                </div>
            </div>

            <!-- Recent Orders & Best Selling Products -->
            <div class="mb-10 grid gap-6 lg:grid-cols-[1.2fr_1.8fr]">
                <!-- Recent Orders -->
                <div v-if="recentOrders.length" class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm" data-aos="fade-up" data-aos-delay="400">
                    <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-lg font-black text-slate-900">Pesanan Terakhir</h3>
                            <p class="mt-1 text-xs text-slate-500">{{ recentOrders.length }} pesanan</p>
                        </div>
                        <span class="inline-block rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                            <i class="fas fa-history mr-1"></i>Terbaru
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div v-for="order in sortedRecentOrders.slice(0, 4)" :key="order.id" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 p-4 transition duration-200 hover:bg-slate-100 hover:border-slate-200">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600 flex-shrink-0">
                                <i class="fas fa-box text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-900">Order #{{ order.id }}</p>
                                <p class="text-xs text-slate-500">{{ new Date(order.created_at).toLocaleDateString('id-ID') }}</p>
                            </div>
                            <span class="flex-shrink-0 inline-block rounded-lg px-2.5 py-1 text-xs font-semibold" :class="{ 'bg-yellow-100 text-yellow-700': order.status === 'pending', 'bg-blue-100 text-blue-700': order.status === 'processing', 'bg-emerald-100 text-emerald-700': order.status === 'completed', 'bg-red-100 text-red-700': order.status === 'cancelled' }">
                                {{ order.status }}
                            </span>
                        </div>
                    </div>

                    <Link :href="route('orders.index')" class="mt-4 block rounded-lg bg-slate-50 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition duration-200 hover:bg-slate-100">
                        Lihat Semua Pesanan →
                    </Link>
                </div>

                <!-- Best Selling Products -->
                <div v-if="bestSellingProducts.length" class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm" data-aos="fade-up" data-aos-delay="500">
                    <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-lg font-black text-slate-900">Produk Terlaris</h3>
                            <p class="mt-1 text-xs text-slate-500">Pilihan favorit pembeli</p>
                        </div>
                        <span class="inline-block rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-700">
                            <i class="fas fa-fire mr-1"></i>Trending
                        </span>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div v-for="product in sortedBestSellingProducts.slice(0, 2)" :key="product.id" class="group overflow-hidden rounded-xl border border-slate-100 bg-slate-50 transition duration-300 hover:shadow-md hover:border-slate-200">
                            <div class="relative h-40 w-full overflow-hidden bg-slate-200">
                                <img :src="'/storage/' + product.image" :alt="product.name" class="h-full w-full object-cover group-hover:scale-110 transition duration-300" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                            </div>
                            <div class="p-4">
                                <h4 class="line-clamp-2 font-bold text-slate-900">{{ product.name }}</h4>
                                <p class="mt-2 text-sm text-slate-600">Terjual {{ product.sold_count || 0 }} kali</p>
                                <p class="mt-2 text-base font-bold text-emerald-600">Rp {{ Number(product.price).toLocaleString('id-ID') }}</p>
                                <Link :href="route('marketplace.show', product.id)" class="mt-3 block rounded-lg bg-emerald-600 px-3 py-2 text-center text-xs font-bold text-white transition duration-200 hover:bg-emerald-700">
                                    Lihat Detail
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Stores Section -->
            <div v-if="topStores.length" class="mb-10 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm" data-aos="fade-up" data-aos-delay="600">
                <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-900">⭐ Seller Terbaik</h3>
                        <p class="mt-1 text-xs text-slate-500">Seller dengan rating tertinggi di Lumira</p>
                    </div>
                    <span class="inline-block rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-700">
                        <i class="fas fa-crown mr-1"></i>Top Seller
                    </span>
                </div>

                <div class="space-y-2">
                    <div v-for="(store, index) in sortedTopStores.slice(0, 5)" :key="store.id" class="flex items-center gap-4 rounded-xl border border-slate-100 bg-slate-50 p-4 transition duration-200 hover:bg-slate-100 hover:border-slate-200">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-yellow-400 to-orange-400 font-bold text-white">
                            {{ index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-900 truncate">{{ store.name }}</p>
                            <p class="text-xs text-slate-500">{{ store.reviews_count || 0 }} review</p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="flex items-center gap-1 rounded-lg bg-yellow-50 px-2.5 py-1 text-sm font-bold text-yellow-700">
                                <i class="fas fa-star text-xs"></i>{{ store.rating || 0 }}
                            </span>
                            <Link v-if="store.slug" :href="route('store.show', store.slug)" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white transition duration-200 hover:bg-emerald-700">
                                Kunjungi
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest Products Section -->
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm" data-aos="fade-up" data-aos-delay="700">
                <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-900">📦 Produk Terbaru</h3>
                        <p class="mt-1 text-xs text-slate-500">Produk baru yang baru ditambahkan</p>
                    </div>
                    <Link :href="route('marketplace')" class="inline-block rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 transition duration-200 hover:bg-emerald-200">
                        <i class="fas fa-arrow-right mr-1"></i>Lihat Semua
                    </Link>
                </div>

                <div v-if="latestProducts.length" class="grid gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <div v-for="product in sortedLatestProducts.slice(0, 8)" :key="product.id" class="group overflow-hidden rounded-xl border border-slate-100 bg-white transition duration-300 hover:shadow-lg hover:border-emerald-200">
                        <div class="relative h-44 w-full overflow-hidden bg-slate-200">
                            <img :src="'/storage/' + product.image" :alt="product.name" class="h-full w-full object-cover group-hover:scale-110 transition duration-300" />
                            <div class="absolute top-3 right-3">
                                <span class="inline-block rounded-lg bg-emerald-600 px-2.5 py-1 text-xs font-bold text-white">Baru</span>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                        </div>
                        <div class="p-4">
                            <h4 class="line-clamp-2 font-bold text-slate-900 group-hover:text-emerald-600 transition">{{ product.name }}</h4>
                            <p class="mt-2 text-xs text-slate-500 truncate">{{ product.store?.name || 'Toko' }}</p>
                            <p class="mt-3 text-lg font-black text-emerald-600">Rp {{ Number(product.price).toLocaleString('id-ID') }}</p>
                            <Link :href="route('marketplace.show', product.id)" class="mt-4 block rounded-lg bg-emerald-600 px-3 py-2 text-center text-xs font-bold text-white transition duration-200 hover:bg-emerald-700">
                                <i class="fas fa-shopping-cart mr-2"></i>Lihat Detail
                            </Link>
                        </div>
                    </div>
                </div>

                <div v-else class="rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                    <i class="fas fa-inbox text-4xl text-slate-300"></i>
                    <p class="mt-3 text-sm font-semibold text-slate-600">Belum ada produk terbaru saat ini</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
