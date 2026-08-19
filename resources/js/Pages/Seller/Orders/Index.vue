<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    orders: { type: Array, default: () => [] },
})

const sortedOrders = computed(() => {
    return [...props.orders].sort((a, b) => {
        const dateDifference = new Date(b.order?.created_at) - new Date(a.order?.created_at)

        return dateDifference || b.id - a.id
    })
})

const formatCurrency = (value) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value || 0))

const statusClasses = {
    pending: 'bg-amber-100 text-amber-700 ring-amber-200',
    paid: 'bg-emerald-100 text-emerald-700 ring-emerald-200',
    shipped: 'bg-blue-100 text-blue-700 ring-blue-200',
    completed: 'bg-emerald-100 text-emerald-700 ring-emerald-200',
    cancelled: 'bg-red-100 text-red-700 ring-red-200',
}

const statusLabel = {
    pending: 'Menunggu',
    paid: 'Sudah Dibayar',
    shipped: 'Dikirim',
    completed: 'Selesai',
    cancelled: 'Dibatalkan',
}

const shipOrder = (id) => {
    router.post(route('seller.orders.ship', id))
}

const completeOrder = (id) => {
    router.post(route('seller.orders.complete', id))
}
</script>

<template>
    <Head title="Pesanan Masuk" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Orders</p>
                    <h2 class="mt-2 text-2xl font-black text-[#0b2617] md:text-3xl">Pesanan Masuk</h2>
                </div>
                <div class="rounded-full bg-white px-4 py-2 text-sm font-bold text-[#0c7c43] shadow-sm ring-1 ring-green-100">
                    {{ orders.length }} pesanan aktif
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <div v-if="orders.length === 0" class="rounded-[2rem] border border-dashed border-slate-300 bg-white p-12 text-center shadow-sm">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-2xl text-[#0c7c43]">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3 class="mt-5 text-2xl font-black text-slate-900">Belum ada pesanan masuk</h3>
                <p class="mt-2 text-slate-500">Pesanan pelanggan akan muncul di sini setelah mereka checkout.</p>
            </div>

            <div v-else class="space-y-5">
                <div
                    v-for="item in sortedOrders"
                    :key="item.id"
                    class="overflow-hidden rounded-[2rem] bg-white shadow-[0_18px_42px_rgba(15,23,42,0.08)] ring-1 ring-slate-200 transition duration-300 hover:-translate-y-0.5"
                >
                    <div class="flex flex-col gap-5 border-b border-slate-100 p-5 md:flex-row md:items-center md:justify-between md:p-6">
                        <div class="flex items-center gap-4">
                            <img v-if="item.product?.image" :src="`/storage/${item.product.image}`" :alt="item.product?.name" class="h-20 w-20 rounded-2xl object-cover ring-1 ring-slate-200" />
                            <div v-else class="flex h-20 w-20 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 ring-1 ring-slate-200"><i class="fas fa-image"></i></div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900">{{ item.product?.name || 'Produk' }}</h3>
                                <p class="mt-1 text-sm text-slate-500">Pembeli: {{ item.order?.user?.name || '-' }}</p>
                            </div>
                        </div>

                        <span :class="statusClasses[item.order?.status] || 'bg-slate-100 text-slate-700 ring-slate-200'" class="inline-flex rounded-full px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.18em] ring-1">
                            {{ statusLabel[item.order?.status] || item.order?.status || 'Status' }}
                        </span>
                    </div>

                    <div class="grid gap-4 p-5 md:grid-cols-3 md:p-6">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Qty</p>
                            <p class="mt-3 text-2xl font-black text-slate-900">{{ item.quantity }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Total</p>
                            <p class="mt-3 text-2xl font-black text-[#0c7c43]">{{ formatCurrency(item.price * item.quantity) }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Pembayaran</p>
                            <p class="mt-3 text-lg font-black text-slate-900">{{ item.order?.payment_method || '-' }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-100 p-5 md:flex-row md:items-center md:justify-between md:p-6">
                        <div class="text-sm text-slate-500">
                            <span class="font-bold text-slate-700">Harga satuan:</span> {{ formatCurrency(item.price) }}
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <button
                                v-if="['pending', 'paid'].includes(item.order?.status)"
                                @click="shipOrder(item.id)"
                                class="rounded-2xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-700"
                            >
                                <i class="fas fa-truck mr-2"></i>Kirim Pesanan
                            </button>

                            <button
                                v-if="item.order?.status === 'shipped'"
                                @click="completeOrder(item.id)"
                                class="rounded-2xl bg-[#0c7c43] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#0a6d3a]"
                            >
                                <i class="fas fa-check-circle mr-2"></i>Selesaikan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
