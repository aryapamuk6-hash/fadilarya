
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    orders: Array,
})

const sortedOrders = computed(() => {
    return [...(props.orders ?? [])].sort((a, b) => {
        const dateDifference = new Date(b.created_at) - new Date(a.created_at)

        return dateDifference || b.id - a.id
    })
})

const form = useForm({
    rating: 5,
    comment: '',
})

function submitReview(orderId)
{
    form.post(
        route('reviews.store', orderId),
        {
            preserveScroll: true,
            onSuccess: () => {
                form.reset()
            }
        }
    )
}

const startChat = (orderId) => {
    router.post(route('chat.start-order', orderId))
}
</script>

<template>
    <Head title="Pesanan Saya" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Transaksi</p>
                    <h2 class="mt-2 text-2xl font-black text-[#0b2617] md:text-3xl">
                        Pesanan Saya
                    </h2>
                </div>
                <div class="rounded-full bg-white px-4 py-2 text-sm font-bold text-[#0c7c43] shadow-sm ring-1 ring-green-100">
                    <i class="fas fa-box mr-2"></i>{{ sortedOrders.length }} pesanan
                </div>
            </div>
        </template>

        <div class="min-h-screen overflow-x-hidden bg-[#f6f8f6]">
            <!-- Empty State -->
            <div
                v-if="sortedOrders.length === 0"
                class="mx-auto max-w-7xl px-6 py-16 lg:px-8"
                data-aos="fade-up"
            >
                <div class="rounded-[2rem] border-2 border-dashed border-green-200 bg-gradient-to-br from-[#edf9ee] to-[#f6f8f6] p-12 text-center shadow-sm">
                    <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-white shadow-lg ring-1 ring-green-100">
                        <i class="fas fa-inbox text-4xl text-slate-400"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900">Belum ada pesanan</h3>
                    <p class="mt-3 text-slate-500">Mulai belanja sekarang dan pesanan Anda akan muncul di sini.</p>
                    <a href="/" class="btn-shine mt-6 inline-flex items-center gap-2 rounded-2xl bg-[#0c7c43] px-6 py-3 font-bold text-white shadow-lg transition hover:-translate-y-1">
                        <i class="fas fa-shopping-bag"></i>
                        Mulai Belanja
                    </a>
                </div>
            </div>

            <!-- Orders List -->
            <div v-else class="mx-auto max-w-7xl px-6 py-8 lg:px-8 space-y-4">
                <div
                    v-for="(order, idx) in sortedOrders"
                    :key="order.id"
                    data-aos="fade-up"
                    :data-aos-delay="idx * 50"
                    class="rounded-[1.5rem] bg-white shadow-sm ring-1 ring-slate-100 transition duration-300 hover:shadow-lg hover:shadow-green-900/10 overflow-hidden"
                >
                    <!-- Order Header -->
                    <div class="border-b border-slate-100 bg-gradient-to-r from-[#edf9ee] via-white to-[#f3faf5] p-5">
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:items-center">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Order ID</p>
                                <p class="mt-1 text-lg font-black text-slate-900">#{{ String(order.id).padStart(5, '0') }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Status</p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span v-if="order.status === 'pending'" class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                        <i class="fas fa-hourglass-half"></i>Menunggu
                                    </span>
                                    <span v-else-if="order.status === 'processing'" class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700">
                                        <i class="fas fa-cog"></i>Diproses
                                    </span>
                                    <span v-else-if="order.status === 'shipped'" class="inline-flex items-center gap-1 rounded-full bg-purple-100 px-3 py-1 text-xs font-bold text-purple-700">
                                        <i class="fas fa-truck"></i>Dikirim
                                    </span>
                                    <span v-else-if="order.status === 'completed'" class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-[#0c7c43]">
                                        <i class="fas fa-check-circle"></i>Selesai
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                        <i class="fas fa-times-circle"></i>Dibatalkan
                                    </span>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Pembayaran</p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span v-if="order.payment_method === 'balance'" class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-600">
                                        <i class="fas fa-wallet"></i>Saldo
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1 rounded-full bg-green-50 px-3 py-1 text-xs font-bold text-green-600">
                                        <i class="fas fa-money-bill"></i>COD
                                    </span>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Total</p>
                                <p class="mt-1 text-xl font-black text-[#0c7c43]">Rp {{ Number(order.total_price).toLocaleString('id-ID') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="divide-y divide-slate-100 p-5">
                        <div
                            v-for="item in order.items"
                            :key="item.id"
                            class="grid gap-4 py-4 sm:grid-cols-[100px_1fr_auto] first:pt-0 last:pb-0"
                        >
                            <!-- Product Image -->
                            <div class="overflow-hidden rounded-xl bg-gradient-to-br from-[#edf9ee] to-[#f6f8f6]">
                                <div v-if="item.product?.image" class="h-24 w-full">
                                    <img :src="`/storage/${item.product.image}`" :alt="item.product?.name || 'Produk'" class="h-full w-full object-cover" />
                                </div>
                                <div v-else class="flex h-24 w-full items-center justify-center text-slate-400">
                                    <i class="fas fa-box text-2xl"></i>
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div>
                                <h4 class="font-black text-slate-900 line-clamp-2">{{ item.product?.name || 'Produk sudah tidak tersedia' }}</h4>
                                <p class="mt-1 text-xs text-slate-500">{{ item.quantity }}x @ Rp {{ Number(item.price).toLocaleString('id-ID') }}</p>
                                <p class="mt-2 text-sm font-bold text-[#0c7c43]">Rp {{ Number(item.price * item.quantity).toLocaleString('id-ID') }}</p>
                            </div>

                            <!-- Subtotal -->
                            <div class="text-right font-bold text-slate-900">
                                Rp {{ Number(item.price * item.quantity).toLocaleString('id-ID') }}
                            </div>
                        </div>
                    </div>

                    <!-- Review Section -->
                    <div v-if="order.status === 'completed' && !order.review && order.items.some((item) => item.product)" class="border-t border-slate-100 bg-gradient-to-r from-amber-50 to-yellow-50 p-5">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-200 text-amber-700">
                                <i class="fas fa-star"></i>
                            </div>
                            <h4 class="font-black text-slate-900">Bagikan Review Produk</h4>
                        </div>

                        <form @submit.prevent="submitReview(order.id)" class="space-y-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-slate-600 mb-2">Rating</label>
                                <div class="flex gap-2">
                                    <button
                                        v-for="i in 5"
                                        :key="i"
                                        type="button"
                                        @click="form.rating = i"
                                        class="text-2xl transition hover:scale-125"
                                        :class="form.rating >= i ? 'text-amber-400' : 'text-slate-300'"
                                    >
                                        ⭐
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-slate-600 mb-2">Komentar</label>
                                <textarea
                                    v-model="form.comment"
                                    rows="3"
                                    placeholder="Bagikan pengalaman Anda dengan produk ini..."
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#0c7c43] focus:ring-2 focus:ring-green-100"
                                />
                            </div>

                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 px-6 py-2 font-bold text-white shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl"
                            >
                                <i class="fas fa-paper-plane"></i>
                                Kirim Review
                            </button>
                        </form>
                    </div>

                    <!-- Review Display -->
                    <div v-if="order.review" class="border-t border-slate-100 bg-gradient-to-r from-green-50 to-emerald-50 p-5">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-200 text-green-700">
                                <i class="fas fa-check"></i>
                            </div>
                            <h4 class="font-black text-slate-900">Review Anda</h4>
                        </div>

                        <div class="mb-3">
                            <div class="flex items-center gap-1 text-lg">
                                <span v-for="i in 5" :key="i" :class="order.review.rating >= i ? 'text-amber-400' : 'text-slate-300'">⭐</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ order.review.rating }}/5 rating</p>
                        </div>

                        <p class="text-sm text-slate-700 leading-6">{{ order.review.comment }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t border-slate-100 bg-slate-50 p-5 flex flex-wrap gap-3 items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Tanggal Pembelian</p>
                            <p class="font-black text-slate-800">{{ new Date(order.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
                        </div>
                        
                        <div class="flex gap-2 flex-wrap justify-end">
                            <button
                                v-if="order.items.some((item) => item.product?.store_id || item.product?.store)"
                                @click="startChat(order.id)"
                                class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold bg-gradient-to-r from-[#0c7c43] to-[#0a5f36] text-white shadow-md hover:-translate-y-1 hover:shadow-lg transition"
                            >
                                <i class="fas fa-comment-dots"></i>
                                Chat Penjual
                            </button>
                            
                            <a
                                v-if="order.items[0]?.product"
                                :href="route('marketplace.show', order.items[0]?.product_id)"
                                class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold border-2 border-slate-300 text-slate-700 hover:border-[#0c7c43] hover:text-[#0c7c43] hover:bg-[#f0fdf4] transition"
                            >
                                Lihat Produk
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

