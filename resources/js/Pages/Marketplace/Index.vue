
<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { computed, reactive } from 'vue'
import AppFooter from '@/Components/AppFooter.vue'
import BackButton from '@/Components/BackButton.vue'

const props = defineProps({
    products: {
        type: [Array, Object],
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    stats: {
        type: Object,
        default: () => ({}),
    },
    topWishlistProducts: {
        type: Array,
        default: () => [],
    },
})

const productList = computed(() => {
    if (Array.isArray(props.products)) {
        return props.products.sort((a, b) => a.id - b.id)
    }

    const data = props.products?.data ?? []
    return data.sort((a, b) => a.id - b.id)
})

const paginationLinks = computed(() => props.products?.links ?? [])

const filter = reactive({
    search: props.filters?.search ?? '',
    category: props.filters?.category ?? '',
    sort: props.filters?.sort ?? '',
})

const page = usePage()

const formatCurrency = (value) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value || 0))

const toggleWishlist = (productId) => {
    if (page.props.auth?.user?.role !== 'user') {
        return
    }

    router.post(route('wishlist.store', productId), {}, {
        preserveScroll: true,
    })
}

const highlightTags = ['Material daur ulang', 'Hemat biaya', 'Ekologis', 'Produk unggulan']

const applyFilter = () => {
    router.get(
        route('marketplace'),
        {
            search: filter.search,
            category: filter.category,
            sort: filter.sort,
        },
        {
            preserveState: true,
            replace: true,
        }
    )
}

const resetFilter = () => {
    filter.search = ''
    filter.category = ''
    filter.sort = ''

    router.get(route('marketplace'))
}
</script>

<template>
    <Head title="Marketplace" />

    <div class="min-h-screen bg-[#f5faf6] text-slate-800">
        <header class="relative overflow-hidden bg-gradient-to-br from-[#edf9ee] via-white to-[#f3faf5] text-slate-800">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(12,124,67,0.08),_transparent_30%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_right,_rgba(12,124,67,0.05),_transparent_40%)]"></div>
            
            <div class="relative mx-auto max-w-7xl px-6 py-12 lg:px-8">
                <div class="mb-8 flex items-center justify-between" data-aos="fade-down" data-aos-duration="600">
                    <div class="flex items-center gap-3">
                        <img src="/images/lumira.png" alt="LUMIRA" class="h-14 w-14" />
                        <div>
                            <p class="text-lg font-black tracking-wide text-[#0b2617]">LUMIRA</p>
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500">Layanan Ulang Material</p>
                        </div>
                    </div>
                    <BackButton fallback="/dashboard" />
                </div>

                <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
                    <div data-aos="fade-right" data-aos-duration="700" data-aos-delay="100">
                        <div class="mb-4 inline-block rounded-full bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.2em] text-[#0c7c43] shadow-sm ring-1 ring-green-100">
                            <i class="fas fa-sparkles mr-2"></i>Belanja Cerdas
                        </div>
                        
                        <h1 class="mt-4 text-4xl font-black leading-tight text-[#0b2617] md:text-5xl">
                            Marketplace <span class="text-[#0c7c43]">barang daur ulang</span>
                        </h1>
                        <p class="mt-5 max-w-xl text-base leading-7 text-slate-600 md:text-lg">
                            Temukan produk berkualitas, ramah lingkungan, dan terjangkau untuk kebutuhan Anda.
                        </p>

                        <div class="mt-7 flex flex-wrap gap-2">
                            <span v-for="tag in highlightTags" :key="tag" class="animate-fade-in rounded-full bg-white px-3 py-1.5 text-xs font-bold text-[#0c7c43] shadow-sm ring-1 ring-green-100 transition" :style="{ animationDelay: Math.random() * 300 + 'ms' }">
                                <i class="fas fa-check mr-1"></i>{{ tag }}
                            </span>
                        </div>
                    </div>

                    <div class="relative" data-aos="zoom-in" data-aos-duration="700" data-aos-delay="200">
                        <div class="absolute -inset-4 rounded-3xl bg-gradient-to-br from-[#0c7c43]/10 via-transparent to-[#0c7c43]/5 blur-2xl"></div>
                        <div class="relative rounded-3xl bg-gradient-to-br from-white to-[#f9faf9] p-6 shadow-lg ring-1 ring-slate-200">
                            <div class="flex items-center justify-between gap-4 rounded-2xl bg-gradient-to-r from-[#0c7c43] to-[#0b2617] p-4 text-white">
                                <div>
                                    <p class="text-xs font-bold uppercase opacity-90">Total Produk</p>
                                    <p class="mt-2 text-3xl font-black">{{ productList.length }}+</p>
                                </div>
                                <div class="text-5xl opacity-20">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>

                            <div class="mt-4 space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                        <i class="fas fa-check text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Harga Terjangkau</p>
                                        <p class="text-xs text-slate-500">Hemat hingga 70%</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                        <i class="fas fa-truck text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Pengiriman Cepat</p>
                                        <p class="text-xs text-slate-500">1-3 hari kerja</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-[#0c7c43]">
                                        <i class="fas fa-leaf text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Ramah Lingkungan</p>
                                        <p class="text-xs text-slate-500">Beli dengan hati</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <section class="mb-8 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100 md:p-5">
                <div class="grid gap-4 md:grid-cols-4">
                    <input
                        v-model="filter.search"
                        type="text"
                        placeholder="Cari produk..."
                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-700 outline-none transition focus:border-emerald-400 focus:bg-white"
                    >

                    <select
                        v-model="filter.category"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-700 outline-none transition focus:border-emerald-400 focus:bg-white"
                    >
                        <option value="">Semua Kategori</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>

                    <select
                        v-model="filter.sort"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-700 outline-none transition focus:border-emerald-400 focus:bg-white"
                    >
                        <option value="">Urutkan</option>
                        <option value="price_low">Harga Termurah</option>
                        <option value="price_high">Harga Termahal</option>
                        <option value="best_seller">Terlaris</option>
                    </select>

                    <div class="flex gap-2">
                        <button
                            @click="applyFilter"
                            class="flex-1 rounded-xl bg-emerald-600 px-4 py-3 font-medium text-white transition hover:bg-emerald-700"
                        >
                            Terapkan
                        </button>

                        <button
                            @click="resetFilter"
                            class="rounded-xl bg-slate-200 px-4 py-3 font-medium text-slate-700 transition hover:bg-slate-300"
                        >
                            Reset
                        </button>
                    </div>
                </div>
            </section>

            <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Produk terbaru</p>
                    <h2 class="mt-2 text-3xl font-black text-[#0b2617]">Pilih item favorit Anda</h2>
                </div>
                <div class="rounded-full bg-white px-4 py-2 text-sm font-bold text-slate-600 shadow-sm ring-1 ring-slate-200">
                    {{ productList.length }} item tersedia
                </div>
            </div>

            <div v-if="productList.length === 0" class="rounded-[2rem] border border-dashed border-slate-300 bg-white p-12 text-center shadow-sm">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-2xl text-[#0c7c43]">
                    <i class="fas fa-store"></i>
                </div>
                <h3 class="mt-5 text-2xl font-black text-slate-900">Belum ada produk</h3>
                <p class="mt-2 text-slate-500">Produk yang baru ditambahkan akan muncul di sini.</p>
            </div>

            <div v-else class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div
                    v-for="product in productList"
                    :key="product.id"
                    class="group overflow-hidden rounded-[1.8rem] border border-slate-200 bg-white shadow-[0_10px_35px_rgba(15,23,42,0.06)] transition-all duration-300 hover:-translate-y-2 hover:border-emerald-200 hover:shadow-[0_24px_60px_rgba(12,124,67,0.16)]"
                >
                    <div class="relative overflow-hidden">
                        <Link :href="route('marketplace.show', product.id)" class="block">
                            <div class="relative overflow-hidden">
                                <img v-if="product.image" :src="`/storage/${product.image}`" :alt="product.name" class="h-64 w-full object-cover transition duration-500 group-hover:scale-110">
                                <div v-else class="flex h-64 items-center justify-center bg-slate-100 text-slate-400"><i class="fas fa-image text-4xl"></i></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/20 via-transparent to-transparent"></div>
                            </div>
                        </Link>

                        <div class="absolute inset-x-4 top-4 flex items-center justify-between">
                            <span class="rounded-full bg-white/90 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#0c7c43] shadow-sm backdrop-blur-sm">
                                {{ product.category?.name || 'Baru' }}
                            </span>

                            <button
                                v-if="page.props.auth?.user?.role === 'user'"
                                type="button"
                                @click.prevent.stop="toggleWishlist(product.id)"
                                class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white/90 text-slate-500 shadow-sm transition hover:border-pink-200 hover:text-pink-500"
                                aria-label="Tambah wishlist"
                            >
                                <i class="far fa-heart text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <Link :href="route('marketplace.show', product.id)" class="block p-5">
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.22em] text-slate-400">
                                    {{ product.store?.name || 'Store' }}
                                </p>
                                <h3 class="mt-2 line-clamp-2 text-xl font-black leading-tight text-slate-900">
                                    {{ product.name }}
                                </h3>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-2xl font-black text-[#0c7c43]">{{ formatCurrency(product.price) }}</p>
                                </div>
                                <span class="rounded-full bg-[#edf9ee] px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#0c7c43]">
                                    {{ product.stock || 0 }} stok
                                </span>
                            </div>

                            <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-sm text-slate-500">
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-star text-amber-400"></i>
                                    {{ Number(product.sold_count || 0) }} terjual
                                </span>
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700">
                                    Lihat detail
                                </span>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>

            <div v-if="paginationLinks.length" class="mt-8 flex flex-wrap items-center justify-center gap-2">
                <Link
                    v-for="link in paginationLinks"
                    :key="link.label"
                    :href="link.url || '#'
                    "
                    v-html="link.label"
                    class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:border-emerald-400 hover:text-emerald-600"
                    :class="{
                        'bg-emerald-600 text-white border-emerald-600 hover:text-white': link.active,
                    }"
                />
            </div>
        </main>
    </div>
</template>
