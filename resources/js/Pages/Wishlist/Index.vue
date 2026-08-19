<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    wishlists: Array,
})

const sortedWishlists = computed(() => {
    return [...props.wishlists].sort((a, b) => a.id - b.id)
})

const removeWishlist = (productId) => {
    if (confirm('Hapus produk dari wishlist?')) {
        router.delete(route('wishlist.destroy', productId))
    }
}

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(price ?? 0))
}
</script>

<template>
    <Head title="Wishlist Saya" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl py-8 px-4">
            <!-- Hero Header -->
            <div class="mb-10" data-aos="fade-up" data-aos-duration="600">
                <div class="rounded-3xl bg-gradient-to-r from-pink-500 via-rose-500 to-red-500 p-8 text-white shadow-2xl shadow-pink-500/30 md:p-12">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-3xl font-black md:text-4xl">
                                <i class="fas fa-heart mr-3"></i>Wishlist Saya
                            </h1>
                            <p class="mt-3 text-pink-100">Koleksi produk favorit yang ingin kamu beli</p>
                            <div class="mt-4 inline-block rounded-2xl bg-white/20 px-4 py-2 text-sm font-bold backdrop-blur-sm">
                                <i class="fas fa-star mr-2 text-yellow-300"></i>{{ wishlists.length }} Produk Tersimpan
                            </div>
                        </div>
                        <Link
                            :href="route('marketplace')"
                            class="w-fit rounded-2xl bg-white px-6 py-3 text-sm font-bold text-rose-600 shadow-lg transition hover:-translate-y-1 hover:shadow-xl"
                        >
                            <i class="fas fa-shopping-bag mr-2"></i>Lanjut Belanja
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="wishlists.length === 0" class="rounded-3xl bg-gradient-to-br from-slate-50 to-slate-100 p-12 text-center shadow-sm ring-1 ring-slate-200" data-aos="zoom-in" data-aos-duration="600">
                <div class="mb-6 text-8xl" data-aos="bounce" data-aos-duration="1000">
                    <i class="fas fa-heart text-slate-300"></i>
                </div>

                <h2 class="text-3xl font-black text-slate-800">
                    Wishlist Masih Kosong
                </h2>

                <p class="mt-3 text-lg text-slate-600">
                    Jelajahi marketplace dan tambahkan produk favorit ke wishlist
                </p>

                <Link
                    :href="route('marketplace')"
                    class="mt-8 inline-block rounded-2xl bg-gradient-to-r from-[#0c7c43] to-[#0b2617] px-8 py-4 text-sm font-bold text-white shadow-lg shadow-green-900/30 transition hover:-translate-y-1 hover:shadow-xl"
                >
                    <i class="fas fa-store mr-2"></i>Ke Marketplace
                </Link>
            </div>

            <!-- Products List -->
            <div v-else>
                <!-- Stats Bar -->
                <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-3" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                    <div class="rounded-2xl bg-gradient-to-br from-pink-500 to-rose-500 p-6 text-white shadow-lg shadow-pink-500/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold opacity-90">Total Favorit</p>
                                <p class="mt-2 text-3xl font-black">{{ wishlists.length }}</p>
                            </div>
                            <div class="text-5xl opacity-20">
                                <i class="fas fa-heart"></i>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 p-6 text-white shadow-lg shadow-blue-500/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold opacity-90">Siap Dibeli</p>
                                <p class="mt-2 text-3xl font-black">{{ wishlists.length }}</p>
                            </div>
                            <div class="text-5xl opacity-20">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 p-6 text-white shadow-lg shadow-emerald-500/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold opacity-90">Kategori</p>
                                <p class="mt-2 text-3xl font-black">
                                    {{ new Set(wishlists.map(w => w.product.category_id)).size }}
                                </p>
                            </div>
                            <div class="text-5xl opacity-20">
                                <i class="fas fa-tags"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    <div
                        v-for="(item, index) in sortedWishlists"
                        :key="item.id"
                        class="group overflow-hidden rounded-2xl bg-white shadow-md ring-1 ring-slate-200 transition duration-300 hover:-translate-y-2 hover:shadow-2xl hover:ring-pink-200"
                        :data-aos="`fade-up`"
                        :data-aos-duration="`600`"
                        :data-aos-delay="`${(index % 4) * 100}`"
                    >
                        <!-- Image Container -->
                        <div class="relative overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                            <img
                                :src="`/storage/${item.product.image}`"
                                class="h-48 w-full object-cover transition duration-500 group-hover:scale-125"
                                :alt="item.product.name"
                            />
                            <!-- Badge -->
                            <div class="absolute left-3 top-3 rounded-full bg-pink-500 px-3 py-1 text-xs font-bold text-white shadow-lg">
                                <i class="fas fa-heart mr-1"></i>Favorit
                            </div>
                            <!-- Delete Button -->
                            <button
                                @click="removeWishlist(item.product.id)"
                                class="absolute right-3 top-3 flex h-10 w-10 items-center justify-center rounded-full bg-red-500 text-white shadow-lg transition duration-300 hover:bg-red-600 hover:scale-110 active:scale-95"
                                title="Hapus dari wishlist"
                            >
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="flex flex-col p-5">
                            <h3 class="line-clamp-2 text-base font-bold text-slate-800 group-hover:text-rose-600 transition">
                                {{ item.product.name }}
                            </h3>

                            <!-- Category -->
                            <p class="mt-3 text-xs">
                                <span class="inline-block rounded-full bg-gradient-to-r from-pink-100 to-rose-100 px-3 py-1 font-bold text-pink-600">
                                    {{ item.product.category?.name }}
                                </span>
                            </p>

                            <!-- Store -->
                            <p class="mt-2 text-sm text-slate-600">
                                <i class="fas fa-store mr-2 text-slate-400"></i>
                                <span class="font-semibold">{{ item.product.store?.name }}</span>
                            </p>

                            <!-- Rating -->
                            <div class="mt-2 flex items-center gap-1">
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                <span class="text-xs font-bold text-slate-600">4.8 (120 review)</span>
                            </div>

                            <!-- Price -->
                            <p class="mt-3 text-xl font-black text-emerald-600">
                                {{ formatPrice(item.product.price) }}
                            </p>

                            <!-- Action Button -->
                            <Link
                                :href="route('marketplace.show', item.product.id)"
                                class="mt-4 rounded-xl bg-gradient-to-r from-[#0c7c43] to-[#0b2617] px-4 py-2.5 text-center text-sm font-bold text-white shadow-md transition duration-300 hover:shadow-lg active:scale-95"
                            >
                                Lihat Detail
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>