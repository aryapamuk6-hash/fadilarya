<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    products: { type: Array, default: () => [] },
})

const sortedProducts = computed(() => {
    return [...props.products].sort((a, b) => a.id - b.id)
})

const formatCurrency = (value) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value || 0))

const removeProduct = (id) => {
    if (confirm('Hapus produk ini?')) {
        router.post(route('seller.products.delete', id), {
            preserveScroll: true,
        })
    }
}

const totalStock = props.products.reduce((sum, product) => sum + Number(product.stock || 0), 0)
const totalRevenue = props.products.reduce((sum, product) => sum + Number(product.price || 0) * Number(product.sold_count || 0), 0)
</script>

<template>
    <Head title="Produk Saya" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Catalog</p>
                    <h2 class="mt-2 text-2xl font-black text-[#0b2617] md:text-3xl">Produk Saya</h2>
                </div>

                <Link
                    href="/seller/products/create"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#0c7c43] to-[#0b2617] px-5 py-3 text-sm font-black text-white shadow-lg shadow-green-900/20 transition duration-300 hover:-translate-y-0.5 hover:shadow-xl"
                >
                    <i class="fas fa-plus"></i>
                    Tambah Produk
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-[1.75rem] bg-gradient-to-br from-[#edf9ee] to-white p-5 ring-1 ring-green-100 shadow-sm">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Total produk</p>
                    <p class="mt-4 text-3xl font-black text-[#0b2617]">{{ products.length }}</p>
                </div>
                <div class="rounded-[1.75rem] bg-gradient-to-br from-[#eff6ff] to-white p-5 ring-1 ring-blue-100 shadow-sm">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Stok aktif</p>
                    <p class="mt-4 text-3xl font-black text-[#0b2617]">{{ totalStock }}</p>
                </div>
                <div class="rounded-[1.75rem] bg-gradient-to-br from-[#fff7ed] to-white p-5 ring-1 ring-orange-100 shadow-sm">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-500">Pendapatan estimasi</p>
                    <p class="mt-4 text-2xl font-black text-[#0b2617]">{{ formatCurrency(totalRevenue) }}</p>
                </div>
            </div>

            <div v-if="products.length === 0" class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-12 text-center shadow-sm">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-2xl text-[#0c7c43]">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3 class="mt-5 text-2xl font-black text-slate-900">Belum ada produk</h3>
                <p class="mt-2 text-slate-500">Mulai tambahkan produk pertama Anda untuk memperluas toko.</p>
                <Link
                    href="/seller/products/create"
                    class="mt-6 inline-flex items-center rounded-2xl bg-[#0c7c43] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-green-900/20 transition hover:bg-[#0a6d3a]"
                >
                    Tambah produk sekarang
                </Link>
            </div>

            <div v-else class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                <div
                    v-for="product in sortedProducts"
                    :key="product.id"
                    class="group overflow-hidden rounded-[1.75rem] bg-white shadow-[0_18px_40px_rgba(15,23,42,0.08)] ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-[0_25px_60px_rgba(12,124,67,0.15)]"
                >
                    <div class="relative overflow-hidden">
                        <img v-if="product.image" :src="`/storage/${product.image}`" :alt="product.name" class="h-60 w-full object-cover transition duration-500 group-hover:scale-105">
                        <div v-else class="flex h-60 items-center justify-center bg-slate-100 text-slate-400"><i class="fas fa-image text-4xl"></i></div>
                        <div class="absolute left-4 top-4 rounded-full bg-white/90 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#0c7c43] shadow-sm">
                            {{ product.stock > 0 ? 'Ready' : 'Habis' }}
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        <div>
                            <h3 class="line-clamp-2 text-xl font-black text-slate-900">{{ product.name }}</h3>
                            <p class="mt-2 text-2xl font-black text-[#0c7c43]">{{ formatCurrency(product.price) }}</p>
                        </div>

                        <div class="grid grid-cols-3 gap-2 text-center text-xs text-slate-500">
                            <div class="rounded-2xl bg-slate-50 px-2 py-3">
                                <div class="font-bold text-slate-700">Stok</div>
                                <div class="mt-1 text-sm font-black text-slate-900">{{ product.stock }}</div>
                            </div>
                            <div class="rounded-2xl bg-slate-50 px-2 py-3">
                                <div class="font-bold text-slate-700">Terjual</div>
                                <div class="mt-1 text-sm font-black text-slate-900">{{ product.sold_count }}</div>
                            </div>
                            <div class="rounded-2xl bg-slate-50 px-2 py-3">
                                <div class="font-bold text-slate-700">Views</div>
                                <div class="mt-1 text-sm font-black text-slate-900">{{ product.views || 0 }}</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <button
                                @click="removeProduct(product.id)"
                                class="flex-1 rounded-2xl bg-red-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-600"
                            >
                                <i class="fas fa-trash-alt mr-2"></i>Hapus
                            </button>
                            <Link
                                :href="route('seller.products.show', product.id)"
                                class="flex-1 rounded-2xl border border-[#0c7c43] bg-[#edf9ee] px-4 py-2.5 text-center text-sm font-bold text-[#0c7c43] transition hover:bg-[#e2f5e8]"
                            >
                                Kelola
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
