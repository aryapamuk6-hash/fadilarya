<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import AppFooter from '@/Components/AppFooter.vue'
import BackButton from '@/Components/BackButton.vue'

const props = defineProps({
    product: {
        type: Object,
        default: () => ({}),
    },
    isWishlisted: {
        type: Boolean,
        default: false,
    },
})

const quantity = ref(1)
const selectedImage = ref(0)
const activeTab = ref('description')
const page = usePage()
const dismissedFlash = ref(false)

watch(
    () => [page.props.flash?.success, page.props.flash?.error],
    () => {
        dismissedFlash.value = false
    },
)

const mainImage = computed(() => {
    return galleryImages.value[selectedImage.value] || ''
})

const formatCurrency = (value) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value || 0))

const totalPrice = computed(() => Number(props.product?.price || 0) * quantity.value)

const galleryImages = computed(() => {
    const list = []

    if (props.product?.image) {
        list.push(`/storage/${props.product.image}`)
    }

    for (const image of props.product?.images ?? []) {
        if (image.image) list.push(`/storage/${image.image}`)
    }

    return [...new Set(list)]
})

const startChat = (productId) => {
    router.post(route('chat.start', productId))
}

const addToCart = (productId) => {
    if (!props.product?.stock || props.product.stock < 1) return

    router.post(route('cart.add', productId), {
        quantity: quantity.value,
    })
}

const toggleWishlist = (productId) => {
    if (props.isWishlisted) {
        router.delete(route('wishlist.destroy', productId), {
            preserveScroll: true,
        })
        return
    }

    router.post(route('wishlist.store', productId), {}, {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head :title="product?.name || 'Detail Produk'" />

    <div class="min-h-screen bg-[#f5faf6] text-slate-800">
        <div v-if="!dismissedFlash && (page.props.flash?.success || page.props.flash?.error)" class="pointer-events-none fixed inset-x-4 bottom-4 z-[100] sm:left-auto sm:right-5 sm:max-w-md">
            <div v-if="page.props.flash?.success" class="pointer-events-auto flex items-start gap-3 rounded-2xl border border-emerald-200 bg-white/95 p-4 text-emerald-800 shadow-[0_18px_50px_rgba(12,124,67,0.2)] ring-1 ring-emerald-100 backdrop-blur-xl" role="status">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#edf9ee] text-[#0c7c43]"><i class="fas fa-shopping-cart"></i></span>
                <div class="min-w-0 flex-1"><p class="text-xs font-black uppercase tracking-[0.16em] text-[#0c7c43]">Keranjang Lumira</p><p class="mt-1 break-words text-sm font-semibold">{{ page.props.flash.success }}</p></div>
                <button type="button" @click="dismissedFlash = true" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100" aria-label="Tutup pemberitahuan"><i class="fas fa-times"></i></button>
            </div>
            <div v-else class="pointer-events-auto flex items-start gap-3 rounded-2xl border border-red-200 bg-white/95 p-4 text-red-800 shadow-[0_18px_50px_rgba(185,28,28,0.16)] ring-1 ring-red-100 backdrop-blur-xl" role="alert">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600"><i class="fas fa-exclamation-circle"></i></span>
                <div class="min-w-0 flex-1"><p class="text-xs font-black uppercase tracking-[0.16em] text-red-600">Perhatian</p><p class="mt-1 break-words text-sm font-semibold">{{ page.props.flash.error }}</p></div>
                <button type="button" @click="dismissedFlash = true" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100" aria-label="Tutup pemberitahuan"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <!-- Hero Header -->
        <div class="bg-gradient-to-br from-[#edf9ee] via-white to-[#f3faf5] px-4 py-8 shadow-sm sm:px-6 lg:px-8" data-aos="fade-down" data-aos-duration="600">
            <div class="mx-auto max-w-7xl">
                <div class="mb-4">
                    <BackButton fallback="/marketplace" />
                </div>
                <nav class="mb-6 flex items-center gap-3 text-sm text-slate-600">
                    <Link href="/marketplace" class="flex items-center gap-2 font-semibold text-[#0c7c43] transition hover:text-[#0a6d3a]">
                        <img src="/images/lumira.png" alt="LUMIRA" class="h-12 w-12" />
                        <div>
                            <p class="text-sm font-black tracking-wide text-[#0b2617]">LUMIRA</p>
                            <p class="text-[9px] font-bold uppercase tracking-[0.12em] text-slate-500">Marketplace</p>
                        </div>
                    </Link>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-700">{{ product?.category?.name || 'Produk' }}</span>
                    <span class="text-slate-300">/</span>
                    <span class="line-clamp-1 text-slate-500">{{ product?.name }}</span>
                </nav>
                
                <div class="grid gap-6 lg:grid-cols-2">
                    <div data-aos="fade-right" data-aos-duration="600" data-aos-delay="100">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">
                            <i class="fas fa-check-circle mr-2"></i>Produk Pilihan
                        </p>
                        <h1 class="mt-3 text-3xl font-black text-[#0b2617] md:text-4xl">
                            {{ product?.name }}
                        </h1>
                        <div class="mt-4 flex items-center gap-4">
                            <div class="flex items-center gap-1 text-amber-400">
                                <i class="fas fa-star text-sm"></i>
                                <i class="fas fa-star text-sm"></i>
                                <i class="fas fa-star text-sm"></i>
                                <i class="fas fa-star text-sm"></i>
                                <i class="fas fa-star-half-alt text-sm"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-600">{{ product?.store?.rating ?? 0 }}/5 · {{ product?.sold_count || 0 }} terjual</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end gap-3" data-aos="fade-left" data-aos-duration="600" data-aos-delay="100">
                        <div class="rounded-2xl bg-white px-5 py-3 shadow-sm ring-1 ring-slate-200">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Dari</p>
                            <p class="mt-1 font-black text-slate-800">{{ product?.store?.name }}</p>
                        </div>
                        <div class="rounded-2xl bg-emerald-50 px-5 py-3 ring-1 ring-emerald-100">
                            <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Stok</p>
                            <p class="mt-1 font-black text-emerald-700">{{ product?.stock ?? 0 }} pcs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="space-y-5">
                    <div class="overflow-hidden rounded-[2rem] bg-white p-3 shadow-[0_22px_60px_rgba(15,23,42,0.08)] ring-1 ring-slate-200">
                        <img
                            :src="mainImage"
                            :alt="product?.name"
                            class="h-[420px] w-full rounded-[1.5rem] object-cover md:h-[520px]"
                        />
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <button
                            v-for="(image, index) in galleryImages"
                            :key="image"
                            type="button"
                            @click="selectedImage = index"
                            class="overflow-hidden rounded-2xl border transition duration-200"
                            :class="selectedImage === index ? 'border-[#0c7c43] ring-4 ring-green-100' : 'border-slate-200 hover:border-slate-300'"
                        >
                            <img :src="image" :alt="product?.name" class="h-24 w-full object-cover" />
                        </button>
                    </div>
                </div>

                <aside class="rounded-[2rem] bg-white p-6 shadow-[0_22px_60px_rgba(15,23,42,0.08)] ring-1 ring-slate-200 lg:sticky lg:top-8 lg:h-fit">
                    <div class="flex items-center justify-between gap-3">
                        <span class="rounded-full bg-[#edf9ee] px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#0c7c43]">
                            {{ product?.category?.name || 'Produk' }}
                        </span>
                        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-amber-700">
                            {{ product?.sold_count || 0 }} terjual
                        </span>
                    </div>

                    <h1 class="mt-5 text-3xl font-black leading-tight text-slate-900 md:text-4xl">
                        {{ product?.name }}
                    </h1>

                    <div class="mt-5 flex items-center gap-3">
                        <div class="flex items-center gap-1 text-amber-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-sm font-bold text-slate-600">{{ product?.store?.rating ?? 0 }}/5</span>
                    </div>

                    <div v-if="$page.props.auth?.user?.role === 'user'" class="mt-5 flex items-center gap-3">
                        <button
                            type="button"
                            @click="toggleWishlist(product.id)"
                            class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold transition"
                            :class="isWishlisted ? 'bg-pink-100 text-pink-700 ring-1 ring-pink-200' : 'bg-slate-100 text-slate-700 ring-1 ring-slate-200 hover:bg-pink-50 hover:text-pink-600'"
                        >
                            <i :class="isWishlisted ? 'fas fa-heart' : 'far fa-heart'"></i>
                            {{ isWishlisted ? 'Sudah di Wishlist' : 'Wishlist' }}
                        </button>
                    </div>

                    <p class="mt-4 text-3xl font-black text-[#0c7c43]">
                        {{ formatCurrency(product?.price) }}
                    </p>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-[1.25rem] bg-slate-50 p-3 ring-1 ring-slate-200">
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-500">Penjual</p>
                            <p class="mt-2 font-black text-slate-800">{{ product?.store?.name || 'Toko Lumira' }}</p>
                        </div>
                        <div class="rounded-[1.25rem] bg-slate-50 p-3 ring-1 ring-slate-200">
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-500">Stok</p>
                            <p class="mt-2 font-black text-slate-800">{{ product?.stock ?? 0 }} pcs</p>
                        </div>
                        <div class="rounded-[1.25rem] bg-emerald-50 p-3 ring-1 ring-emerald-100 sm:col-span-2">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-emerald-700">Pengiriman</p>
                                    <p class="mt-2 font-black text-emerald-700">Gratis ongkir</p>
                                </div>
                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-lg text-emerald-600 shadow-sm">
                                    <i class="fas fa-truck"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Jumlah</label>
                        <div class="flex w-fit items-center rounded-2xl border border-slate-200 bg-slate-50 p-1">
                            <button type="button" @click="quantity = Math.max(1, quantity - 1)" class="flex h-10 w-10 items-center justify-center rounded-xl text-xl font-bold text-slate-600 transition hover:bg-white">
                                −
                            </button>
                            <span class="w-12 text-center text-lg font-black text-slate-800">{{ quantity }}</span>
                            <button type="button" @click="quantity = Math.min(product.stock, quantity + 1)" :disabled="quantity >= product.stock" class="flex h-10 w-10 items-center justify-center rounded-xl text-xl font-bold text-slate-600 transition hover:bg-white disabled:cursor-not-allowed disabled:opacity-40">
                                +
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl bg-gradient-to-r from-[#0c7c43] to-[#0a5f36] p-4 text-white shadow-lg shadow-green-900/20 ring-1 ring-green-600/30">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold">Total Belanja:</span>
                            <span class="text-2xl font-black">{{ formatCurrency(totalPrice) }}</span>
                        </div>
                    </div>

                    <div v-if="product?.stock < 1" class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>Barang telah habis dan tidak dapat dibeli.
                    </div>

                    <div v-if="$page.props.auth?.user?.role === 'user' && product?.stock > 0" class="mt-6 space-y-3">
                        <button
                            @click="addToCart(product.id)"
                            class="flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#0c7c43] to-[#0a5f36] px-5 py-4 text-sm font-black text-white shadow-lg shadow-green-900/30 transition hover:-translate-y-1 hover:shadow-xl active:translate-y-0"
                        >
                            <i class="fas fa-shopping-cart"></i>
                            Tambah ke Keranjang
                        </button>

                        <button
                            @click="startChat(product.id)"
                            class="flex w-full items-center justify-center gap-2 rounded-2xl border-2 border-[#0c7c43] bg-white px-5 py-4 text-sm font-black text-[#0c7c43] shadow-sm transition hover:bg-[#f0fdf4] hover:-translate-y-1 hover:shadow-md active:translate-y-0"
                        >
                            <i class="fas fa-comments"></i>
                            Chat Penjual
                        </button>
                    </div>
                </aside>
            </div>

            <section class="mt-8 rounded-[2rem] bg-white p-6 shadow-[0_20px_50px_rgba(15,23,42,0.06)] ring-1 ring-slate-200 md:p-8">
                <div class="mb-6 grid gap-4 md:grid-cols-4">
                    <div class="rounded-[1.5rem] bg-[#f7faf7] p-4 ring-1 ring-slate-200">
                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-500">Terjual</p>
                        <p class="mt-3 text-3xl font-black text-slate-900">{{ product?.sold_count || 0 }}</p>
                    </div>
                    <div class="rounded-[1.5rem] bg-[#fffaf0] p-4 ring-1 ring-amber-100">
                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-amber-700">Rating</p>
                        <p class="mt-3 text-3xl font-black text-slate-900">{{ product?.store?.rating ?? 0 }}</p>
                    </div>
                    <div class="rounded-[1.5rem] bg-[#edf9ee] p-4 ring-1 ring-emerald-100">
                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-emerald-700">Pengiriman</p>
                        <p class="mt-3 text-2xl font-black text-slate-900">1-3 hari</p>
                    </div>
                    <div class="rounded-[1.5rem] bg-[#f3f4ff] p-4 ring-1 ring-indigo-100">
                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-indigo-700">Review</p>
                        <p class="mt-3 text-3xl font-black text-slate-900">{{ product?.store?.reviews?.length ?? 0 }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 border-b-2 border-slate-200 pb-4">
                    <button
                        type="button"
                        @click="activeTab = 'description'"
                        class="rounded-lg px-4 py-3 text-sm font-bold transition duration-200"
                        :class="activeTab === 'description' ? 'border-b-2 border-[#0c7c43] text-[#0c7c43]' : 'text-slate-600 hover:text-slate-900'"
                    >
                        <i class="fas fa-file-alt mr-2"></i>Deskripsi
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'reviews'"
                        class="rounded-lg px-4 py-3 text-sm font-bold transition duration-200"
                        :class="activeTab === 'reviews' ? 'border-b-2 border-[#0c7c43] text-[#0c7c43]' : 'text-slate-600 hover:text-slate-900'"
                    >
                        <i class="fas fa-star mr-2"></i>Review
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'details'"
                        class="rounded-lg px-4 py-3 text-sm font-bold transition duration-200"
                        :class="activeTab === 'details' ? 'border-b-2 border-[#0c7c43] text-[#0c7c43]' : 'text-slate-600 hover:text-slate-900'"
                    >
                        <i class="fas fa-info-circle mr-2"></i>Detail Produk
                    </button>
                </div>

                <div class="mt-6">
                    <div v-if="activeTab === 'description'" class="space-y-4 text-slate-600">
                        <p class="text-lg leading-8">
                            {{ product?.description || 'Deskripsi produk belum tersedia.' }}
                        </p>
                        <div class="grid gap-4 md:grid-cols-3">
                            <div class="rounded-2xl bg-[#f8faf8] p-4 ring-1 ring-slate-200">
                                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Kualitas</p>
                                <p class="mt-2 font-bold text-slate-800">Dipilih dengan cermat</p>
                            </div>
                            <div class="rounded-2xl bg-[#f8faf8] p-4 ring-1 ring-slate-200">
                                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Bahan</p>
                                <p class="mt-2 font-bold text-slate-800">Daur ulang & tahan lama</p>
                            </div>
                            <div class="rounded-2xl bg-[#f8faf8] p-4 ring-1 ring-slate-200">
                                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Dukungan</p>
                                <p class="mt-2 font-bold text-slate-800">Chat cepat dengan penjual</p>
                            </div>
                        </div>
                    </div>

                    <div v-else-if="activeTab === 'reviews'" class="space-y-4">
                        <div v-if="product?.store?.reviews?.length" class="space-y-4">
                            <div
                                v-for="review in product.store.reviews"
                                :key="review.id"
                                class="rounded-[1.5rem] bg-slate-50 p-4 ring-1 ring-slate-200"
                            >
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-black text-slate-900">{{ review.user?.name }}</p>
                                    <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">⭐ {{ review.rating }}/5</span>
                                </div>
                                <p class="mt-3 text-sm leading-7 text-slate-600">{{ review.comment }}</p>
                            </div>
                        </div>
                        <div v-else class="rounded-[1.5rem] bg-slate-50 p-8 text-center text-slate-500 ring-1 ring-slate-200">
                            Belum ada review untuk produk ini.
                        </div>
                    </div>

                    <div v-else class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-[1.5rem] bg-slate-50 p-4 ring-1 ring-slate-200">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Kategori</p>
                            <p class="mt-2 text-lg font-black text-slate-800">{{ product?.category?.name || '-' }}</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 p-4 ring-1 ring-slate-200">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Toko</p>
                            <p class="mt-2 text-lg font-black text-slate-800">{{ product?.store?.name || '-' }}</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 p-4 ring-1 ring-slate-200">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Rating toko</p>
                            <p class="mt-2 text-lg font-black text-slate-800">{{ product?.store?.rating ?? 0 }}/5</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-slate-50 p-4 ring-1 ring-slate-200">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Total review</p>
                            <p class="mt-2 text-lg font-black text-slate-800">{{ product?.store?.reviews?.length ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Seller Info Section -->
            <section v-if="product?.store" class="mt-8 rounded-[2rem] bg-white p-6 shadow-[0_20px_50px_rgba(15,23,42,0.06)] ring-1 ring-slate-200 md:p-8">
                <h2 class="text-2xl font-black text-slate-900 mb-6">
                    <i class="fas fa-store mr-3 text-[#0c7c43]"></i>Informasi Penjual
                </h2>
                
                <div class="grid gap-6 md:grid-cols-3">
                    <!-- Seller Card -->
                    <div class="rounded-2xl border-2 border-slate-200 bg-gradient-to-br from-white to-slate-50 p-6 hover:border-[#0c7c43] hover:shadow-lg transition duration-300">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#0c7c43] to-[#0a5f36] text-2xl text-white">
                                <i class="fas fa-store"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-slate-900">{{ product?.store?.name }}</h3>
                                <p class="text-sm text-slate-600">Penjual resmi Lumira</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-100">
                                <span class="text-sm font-bold text-slate-600">Rating</span>
                                <span class="font-black text-amber-600">
                                    <i class="fas fa-star mr-1 text-amber-400"></i>{{ product?.store?.rating ?? 0 }}/5
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-100">
                                <span class="text-sm font-bold text-slate-600">Review</span>
                                <span class="font-black text-slate-900">{{ product?.store?.reviews?.length ?? 0 }} review</span>
                            </div>
                        </div>
                        
                        <button
                            v-if="$page.props.auth?.user?.role === 'user'"
                            @click="startChat(product.id)"
                            class="mt-5 w-full rounded-xl bg-gradient-to-r from-[#0c7c43] to-[#0a5f36] px-4 py-3 text-sm font-black text-white shadow-lg shadow-green-900/20 transition hover:-translate-y-1 hover:shadow-xl"
                        >
                            <i class="fas fa-comment-dots mr-2"></i>Chat Sekarang
                        </button>
                    </div>

                    <!-- Stats -->
                    <div class="space-y-3">
                        <div class="rounded-2xl bg-gradient-to-br from-emerald-50 to-green-50 p-6 ring-1 ring-emerald-200">
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-emerald-700">Produk Terjual</p>
                            <p class="mt-3 text-3xl font-black text-emerald-900">{{ product?.sold_count || 0 }}</p>
                        </div>
                        <div class="rounded-2xl bg-gradient-to-br from-blue-50 to-sky-50 p-6 ring-1 ring-blue-200">
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-blue-700">Produk Aktif</p>
                            <p class="mt-3 text-3xl font-black text-blue-900">{{ product?.store?.products_count ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="space-y-3">
                        <div class="rounded-2xl bg-gradient-to-br from-amber-50 to-orange-50 p-6 ring-1 ring-amber-200">
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-amber-700">Pengiriman</p>
                            <p class="mt-3 font-black text-amber-900">Gratis Ongkir</p>
                            <p class="mt-1 text-sm text-amber-700">Ke seluruh Indonesia</p>
                        </div>
                        <div class="rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 p-6 ring-1 ring-purple-200">
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-purple-700">Respon Cepat</p>
                            <p class="mt-3 font-black text-purple-900">Aktif 24/7</p>
                            <p class="mt-1 text-sm text-purple-700">Siap membantu Anda</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <AppFooter />
    </div>
</template>

