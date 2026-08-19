<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    products: Array,
})

const sortedProducts = computed(() => {
    return [...props.products].sort((a, b) => a.id - b.id)
})

const hideProduct = (id) => {
    router.post(route('admin.products.hide', id))
}

const showProduct = (id) => {
    router.post(route('admin.products.show', id))
}

const deleteProduct = (id) => {
    if (!confirm('Hapus produk ini?')) return
    router.post(route('admin.products.delete', id), {
        preserveScroll: true,
    })
}

const currency = (value) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value || 0))

const statusClass = (status = false) =>
    status ? 'bg-red-100 text-red-700 ring-1 ring-red-200' : 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200'
</script>

<template>
    <Head title="Kelola Produk" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#0c7c43]">Catalog</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Kelola Produk</h2>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-7xl py-8">
            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-slate-100" data-aos="fade-up">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.22em] text-[#0c7c43]">Produk</p>
                        <h3 class="mt-2 text-2xl font-black text-slate-900">Daftar Produk</h3>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#edf9ee] text-[#0c7c43]">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-200 text-sm uppercase tracking-[0.14em] text-slate-400">
                                <th class="py-3 pr-4 font-semibold">Produk</th>
                                <th class="py-3 pr-4 font-semibold">Kategori</th>
                                <th class="py-3 pr-4 font-semibold">Seller</th>
                                <th class="py-3 pr-4 font-semibold">Harga</th>
                                <th class="py-3 pr-4 font-semibold">Status</th>
                                <th class="py-3 pr-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in sortedProducts" :key="product.id" class="border-b border-slate-100 text-sm text-slate-700 last:border-0">
                                <td class="py-4 pr-4 font-semibold text-slate-900">{{ product.name }}</td>
                                <td class="py-4 pr-4">{{ product.category?.name ?? '-' }}</td>
                                <td class="py-4 pr-4">{{ product.store?.name ?? '-' }}</td>
                                <td class="py-4 pr-4 font-bold text-slate-900">{{ currency(product.price) }}</td>
                                <td class="py-4 pr-4">
                                    <span :class="statusClass(product.is_hidden)" class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.12em]">
                                        {{ product.is_hidden ? 'Disembunyikan' : 'Aktif' }}
                                    </span>
                                </td>
                                <td class="py-4 pr-4">
                                    <div class="flex flex-wrap gap-2">
                                        <button v-if="!product.is_hidden" @click="hideProduct(product.id)" class="rounded-xl bg-amber-500 px-3.5 py-2 text-xs font-bold text-white shadow-lg shadow-amber-900/15 transition hover:-translate-y-0.5 hover:bg-amber-600">
                                            Hide
                                        </button>
                                        <button v-else @click="showProduct(product.id)" class="rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-bold text-white shadow-lg shadow-emerald-900/15 transition hover:-translate-y-0.5 hover:bg-emerald-700">
                                            Show
                                        </button>
                                        <button @click="deleteProduct(product.id)" class="rounded-xl bg-red-600 px-3.5 py-2 text-xs font-bold text-white shadow-lg shadow-red-900/15 transition hover:-translate-y-0.5 hover:bg-red-700">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>