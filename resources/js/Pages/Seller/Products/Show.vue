<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

const props = defineProps({
    product: { type: Object, required: true },
    buyers: { type: Array, default: () => [] },
})

const startChatWithBuyer = (buyerId) => {
    if (!buyerId || !confirm('Mulai chat dengan pembeli ini?')) return
    router.post(route('chat.startWith', [props.product.id, buyerId]))
}
</script>

<template>
    <Head :title="`Kelola ${product.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Seller tools</p>
                    <h2 class="mt-2 text-2xl font-black text-[#0b2617] md:text-3xl">Kelola Produk</h2>
                </div>
                <span class="rounded-full bg-white px-4 py-2 text-sm font-bold text-[#0c7c43] shadow-sm ring-1 ring-green-100">
                    {{ product.status === 'active' ? 'Produk aktif' : 'Produk habis' }}
                </span>
            </div>
        </template>

        <div class="space-y-6">
            <section class="overflow-hidden rounded-[2rem] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.08)] ring-1 ring-slate-200">
                <div class="grid gap-0 lg:grid-cols-[0.9fr_1.1fr]">
                    <div class="bg-gradient-to-br from-[#edf9ee] to-[#f6faf7] p-4 sm:p-6">
                        <div v-if="product.image" class="overflow-hidden rounded-[1.5rem] shadow-lg ring-1 ring-green-100">
                            <img :src="`/storage/${product.image}`" :alt="product.name" class="aspect-square w-full object-cover" />
                        </div>
                        <div v-else class="flex aspect-square items-center justify-center rounded-[1.5rem] bg-slate-100 text-slate-400">
                            <i class="fas fa-image text-5xl"></i>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-[#edf9ee] px-3 py-1 text-xs font-black uppercase tracking-wider text-[#0c7c43]">{{ product.category?.name || 'Produk' }}</span>
                            <span class="rounded-full px-3 py-1 text-xs font-black uppercase tracking-wider" :class="product.stock > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'">
                                {{ product.stock > 0 ? 'Ready' : 'Habis' }}
                            </span>
                        </div>
                        <h1 class="mt-5 text-3xl font-black leading-tight text-[#0b2617] md:text-4xl">{{ product.name }}</h1>
                        <p class="mt-3 text-2xl font-black text-[#0c7c43]">Rp {{ Number(product.price).toLocaleString('id-ID') }}</p>
                        <p class="mt-5 whitespace-pre-wrap text-sm leading-7 text-slate-600">{{ product.description }}</p>

                        <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-100"><p class="text-xs font-bold text-slate-500">Stok</p><p class="mt-2 text-2xl font-black text-slate-900">{{ product.stock }}</p></div>
                            <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-100"><p class="text-xs font-bold text-slate-500">Terjual</p><p class="mt-2 text-2xl font-black text-slate-900">{{ product.sold_count || 0 }}</p></div>
                            <div class="col-span-2 rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-100 sm:col-span-1"><p class="text-xs font-bold text-slate-500">Kategori</p><p class="mt-2 truncate font-black text-slate-900">{{ product.category?.name || '-' }}</p></div>
                        </div>

                        <div class="mt-7">
                            <Link :href="route('seller.products.edit', product.id)" class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-2xl bg-[#0c7c43] px-5 py-3 text-sm font-black text-white shadow-lg shadow-green-900/20 transition hover:-translate-y-0.5 hover:bg-[#0b2617] sm:w-auto"><i class="fas fa-pen"></i>Edit Produk</Link>
                        </div>
                    </div>
                </div>
            </section>

            <section v-if="product.images?.length" class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
                <div class="mb-5"><p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Product gallery</p><h3 class="mt-2 text-2xl font-black text-[#0b2617]">Foto Produk</h3></div>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4"><img v-for="image in product.images" :key="image.id" :src="`/storage/${image.image}`" alt="Foto galeri produk" class="aspect-square w-full rounded-2xl object-cover ring-1 ring-slate-200" /></div>
            </section>

            <section class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Customer care</p>
                <h3 class="mt-2 text-2xl font-black text-[#0b2617]">Pembeli Produk</h3>
                <div v-if="buyers.length" class="mt-5 grid gap-3 md:grid-cols-2">
                    <div v-for="buyer in buyers" :key="buyer.id" class="flex flex-col gap-3 rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-100 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0"><p class="truncate font-black text-slate-900">{{ buyer.name }}</p><p class="truncate text-sm text-slate-500">{{ buyer.email }}</p></div>
                        <button type="button" @click="startChatWithBuyer(buyer.id)" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-[#edf9ee] px-4 py-2 text-sm font-bold text-[#0c7c43] hover:bg-[#d9f3df]"><i class="fas fa-comments"></i>Chat</button>
                    </div>
                </div>
                <p v-else class="mt-4 rounded-2xl bg-slate-50 p-5 text-sm text-slate-500">Belum ada pembeli untuk produk ini.</p>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
