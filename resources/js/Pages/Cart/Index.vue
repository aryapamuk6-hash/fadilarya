<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    cart: Object,
});

const form = useForm({
    payment_method: 'balance',
    voucher_code: '',
});

const removeItem = (id) => {
    router.delete(route('cart.remove', id));
};

const updateQuantity = (id, quantity) => {
    router.patch(route('cart.update', id), {
        quantity,
    });
};

const totalPrice = () => {
    let total = 0;

    props.cart.items.forEach((item) => {
        total += item.product.price * item.quantity;
    });

    return total;
};

const checkout = () => {
    form.post(route('checkout.store'));
};
</script>

<template>
    <Head title="Keranjang" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Belanja</p>
                    <h2 class="mt-2 text-2xl font-black text-[#0b2617] md:text-3xl">
                        Keranjang Belanja
                    </h2>
                </div>
                <div class="rounded-full bg-white px-4 py-2 text-sm font-bold text-[#0c7c43] shadow-sm ring-1 ring-green-100">
                    <i class="fas fa-shopping-cart mr-2"></i>{{ cart.items.length }} item
                </div>
            </div>
        </template>

        <div class="min-h-screen overflow-x-hidden bg-[#f6f8f6]">
            <!-- Empty Cart State -->
            <div
                v-if="cart.items.length === 0"
                class="mx-auto max-w-7xl px-6 py-16 lg:px-8"
                data-aos="fade-up"
            >
                <div class="rounded-[2rem] border-2 border-dashed border-green-200 bg-gradient-to-br from-[#edf9ee] to-[#f6f8f6] p-12 text-center shadow-sm">
                    <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-white shadow-lg ring-1 ring-green-100">
                        <i class="fas fa-shopping-cart text-4xl text-slate-400"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900">Keranjang masih kosong</h3>
                    <p class="mt-3 text-slate-500">Jelajahi produk kami dan mulai berbelanja sekarang!</p>
                    <a href="/" class="btn-shine mt-6 inline-flex items-center gap-2 rounded-2xl bg-[#0c7c43] px-6 py-3 font-bold text-white shadow-lg transition hover:-translate-y-1">
                        <i class="fas fa-arrow-left"></i>
                        Lanjut Belanja
                    </a>
                </div>
            </div>

            <!-- Cart Items -->
            <div v-else class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
                <div class="grid gap-8 lg:grid-cols-3">
                    <!-- Items List -->
                    <div class="lg:col-span-2 space-y-4">
                        <div
                            v-for="(item, idx) in cart.items"
                            :key="item.id"
                            data-aos="fade-up"
                            :data-aos-delay="idx * 50"
                            class="group rounded-[1.5rem] bg-white p-5 shadow-sm ring-1 ring-slate-100 transition duration-300 hover:shadow-lg hover:shadow-green-900/10 hover:-translate-y-1"
                        >
                            <div class="grid gap-4 sm:grid-cols-[120px_1fr_auto]">
                                <!-- Product Image -->
                                <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-[#edf9ee] to-[#f6f8f6]">
                                    <div v-if="item.product.image" class="h-28 w-full">
                                        <img :src="`/storage/${item.product.image}`" :alt="item.product.name" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" />
                                    </div>
                                    <div v-else class="flex h-28 w-full items-center justify-center text-slate-400">
                                        <i class="fas fa-box text-3xl"></i>
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div>
                                    <h3 class="text-lg font-black text-slate-900 line-clamp-2">{{ item.product.name }}</h3>
                                    <p class="mt-2 text-sm text-slate-500">dari <span class="font-bold text-[#0c7c43]">{{ item.product.store?.name || 'Toko' }}</span></p>

                                    <div class="mt-3 flex items-center gap-3">
                                        <span class="text-xl font-black text-[#0c7c43]">
                                            Rp {{ Number(item.product.price).toLocaleString('id-ID') }}
                                        </span>
                                        <span v-if="item.product.discount" class="rounded-full bg-red-100 px-2 py-1 text-xs font-bold text-red-600">
                                            -{{ item.product.discount }}%
                                        </span>
                                    </div>
                                    <p v-if="item.product.stock < item.quantity" class="mt-2 text-xs font-bold text-red-600">
                                        <i class="fas fa-exclamation-circle mr-1"></i>Stok tersedia hanya {{ item.product.stock }}.
                                    </p>
                                </div>

                                <!-- Quantity & Actions -->
                                <div class="flex flex-col items-end justify-between sm:flex-row sm:items-center">
                                    <div class="flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 p-1">
                                        <button
                                            @click="updateQuantity(item.id, Math.max(1, item.quantity - 1))"
                                            class="flex h-8 w-8 items-center justify-center rounded-full text-slate-600 transition hover:bg-white hover:text-[#0c7c43]"
                                        >
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <span class="w-8 text-center font-bold">{{ item.quantity }}</span>
                                        <button
                                            @click="updateQuantity(item.id, item.quantity + 1)"
                                            class="flex h-8 w-8 items-center justify-center rounded-full text-slate-600 transition hover:bg-white hover:text-[#0c7c43]"
                                        >
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>

                                    <button
                                        @click="removeItem(item.id)"
                                        class="mt-3 flex items-center gap-2 text-sm font-bold text-red-600 transition hover:text-red-700 sm:mt-0"
                                    >
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary & Checkout -->
                    <div class="lg:col-span-1" data-aos="fade-left">
                        <div class="sticky top-24 space-y-4">
                            <!-- Summary Card -->
                            <div class="rounded-[1.5rem] bg-gradient-to-br from-[#edf9ee] via-white to-[#f3faf5] p-6 shadow-sm ring-1 ring-green-100">
                                <h3 class="text-sm font-black uppercase tracking-[0.2em] text-[#0c7c43]">Ringkasan</h3>

                                <div class="mt-6 space-y-3 border-b border-green-100 pb-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Subtotal</span>
                                        <span class="font-bold text-slate-900">Rp {{ Number(totalPrice()).toLocaleString('id-ID') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Ongkir</span>
                                        <span class="font-bold text-slate-900">-</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Diskon</span>
                                        <span class="font-bold text-slate-900">Rp 0</span>
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-between">
                                    <span class="text-lg font-black text-slate-900">Total</span>
                                    <span class="text-2xl font-black text-[#0c7c43]">Rp {{ Number(totalPrice()).toLocaleString('id-ID') }}</span>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="rounded-[1.5rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
                                <h3 class="text-sm font-black uppercase tracking-[0.2em] text-slate-600">Voucher</h3>
                                <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                                    <input v-model="form.voucher_code" type="text" placeholder="Contoh: LUMIRA10" class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm uppercase outline-none focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100" />
                                </div>
                                <p class="mt-2 text-xs text-slate-500">Masukkan kode voucher jika tersedia.</p>
                            </div>

                            <!-- Payment Method -->
                            <div class="rounded-[1.5rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
                                <h3 class="text-sm font-black uppercase tracking-[0.2em] text-slate-600">Metode Pembayaran</h3>

                                <div class="mt-4 space-y-3">
                                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 border-slate-200 p-3 transition hover:border-[#0c7c43] hover:bg-[#edf9ee]" :class="form.payment_method === 'balance' ? 'border-[#0c7c43] bg-[#edf9ee]' : ''">
                                        <input
                                            type="radio"
                                            value="balance"
                                            v-model="form.payment_method"
                                            class="h-4 w-4"
                                        />
                                        <div>
                                            <p class="font-bold text-slate-900">Saldo Lumira</p>
                                            <p class="text-xs text-slate-500">Bayar dengan saldo akun Anda</p>
                                        </div>
                                        <i class="fas fa-wallet ml-auto text-[#0c7c43]"></i>
                                    </label>

                                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 border-slate-200 p-3 transition hover:border-[#0c7c43] hover:bg-[#edf9ee]" :class="form.payment_method === 'cod' ? 'border-[#0c7c43] bg-[#edf9ee]' : ''">
                                        <input
                                            type="radio"
                                            value="cod"
                                            v-model="form.payment_method"
                                            class="h-4 w-4"
                                        />
                                        <div>
                                            <p class="font-bold text-slate-900">COD (Bayar di Tempat)</p>
                                            <p class="text-xs text-slate-500">Bayar saat barang tiba</p>
                                        </div>
                                        <i class="fas fa-money-bill-wave ml-auto text-[#0c7c43]"></i>
                                    </label>
                                </div>
                            </div>

                            <!-- Checkout Button -->
                            <button
                                @click="checkout"
                                class="btn-shine w-full rounded-2xl bg-gradient-to-r from-[#0c7c43] to-[#0b2617] px-6 py-4 font-black text-white shadow-lg shadow-green-900/30 transition duration-300 hover:-translate-y-1 hover:shadow-2xl"
                            >
                                <i class="fas fa-check-circle mr-2"></i>
                                Lanjut Bayar
                            </button>

                            <a href="/" class="block rounded-2xl border border-slate-200 bg-white px-6 py-3 text-center font-bold text-slate-700 shadow-sm transition hover:border-green-200 hover:text-[#0c7c43]">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>