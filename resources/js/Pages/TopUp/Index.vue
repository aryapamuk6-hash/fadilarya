<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    topUps: Array,
});

const sortedTopUps = computed(() => {
    return [...props.topUps].sort((a, b) => a.id - b.id)
})

const form = useForm({
    amount: '',
    proof: null,
});

const submit = () => {
    form.post(route('topup.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Top Up Saldo" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Dompet Digital</p>
                    <h2 class="mt-2 text-2xl font-black text-[#0b2617] md:text-3xl">
                        Top Up Saldo
                    </h2>
                </div>
                <div class="rounded-full bg-white px-4 py-2 text-sm font-bold text-[#0c7c43] shadow-sm ring-1 ring-green-100">
                    <i class="fas fa-wallet mr-2"></i>Saldo Tersedia
                </div>
            </div>
        </template>

        <div class="min-h-screen overflow-x-hidden bg-[#f6f8f6]">
            <div class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
                <div class="grid gap-8 lg:grid-cols-3">
                    <!-- Form Section -->
                    <div class="lg:col-span-1" data-aos="fade-right">
                        <div class="sticky top-24">
                            <div class="rounded-[1.5rem] bg-gradient-to-br from-[#edf9ee] via-white to-[#f3faf5] p-6 shadow-sm ring-1 ring-green-100">
                                <h3 class="text-sm font-black uppercase tracking-[0.2em] text-[#0c7c43]">Ajukan Top Up</h3>

                                <form @submit.prevent="submit" class="mt-6 space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-600 mb-2">Nominal (Rp)</label>
                                        <input
                                            v-model="form.amount"
                                            type="number"
                                            placeholder="Minimal Rp 50.000"
                                            min="50000"
                                            step="1000"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold focus:border-[#0c7c43] focus:ring-2 focus:ring-green-100"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-600 mb-2">Bukti Transfer</label>
                                        <div class="relative rounded-xl border-2 border-dashed border-green-200 bg-white p-4 text-center transition hover:border-[#0c7c43] hover:bg-[#edf9ee]">
                                            <input
                                                type="file"
                                                @input="form.proof = $event.target.files[0]"
                                                class="absolute inset-0 cursor-pointer opacity-0"
                                                accept="image/*"
                                            />
                                            <div class="pointer-events-none">
                                                <i class="fas fa-cloud-upload-alt text-3xl text-slate-400"></i>
                                                <p class="mt-2 text-xs font-bold text-slate-500">Klik untuk upload bukti pembayaran</p>
                                                <p class="mt-1 text-[11px] text-slate-400">PNG, JPG (max 5MB)</p>
                                                <p v-if="form.proof" class="mt-2 text-xs font-bold text-green-600 truncate">
                                                    <i class="fas fa-check-circle mr-1"></i>{{ form.proof.name }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <button
                                        type="submit"
                                        :disabled="!form.amount || !form.proof"
                                        class="btn-shine w-full rounded-xl bg-gradient-to-r from-[#0c7c43] to-[#0b2617] px-6 py-3 font-black text-white shadow-lg shadow-green-900/30 transition hover:-translate-y-0.5 hover:shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <i class="fas fa-arrow-up-right mr-2"></i>
                                        Kirim Top Up
                                    </button>
                                </form>

                                <!-- Info Box -->
                                <div class="mt-6 rounded-xl bg-white p-4 text-sm text-slate-600 border border-green-100">
                                    <p class="font-bold text-slate-900 flex items-center gap-2 mb-2">
                                        <i class="fas fa-info-circle text-[#0c7c43]"></i>
                                        Informasi
                                    </p>
                                    <ul class="space-y-1 text-xs">
                                        <li>✓ Minimal top up Rp 50.000</li>
                                        <li>✓ Verifikasi dalam 1-2 jam kerja</li>
                                        <li>✓ Bebas biaya admin</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- History Section -->
                    <div class="lg:col-span-2 space-y-4" data-aos="fade-left">
                        <div>
                            <h3 class="mb-4 text-xl font-black text-slate-900">Riwayat Top Up</h3>

                            <div v-if="topUps.length === 0" class="rounded-[1.5rem] border-2 border-dashed border-green-200 bg-gradient-to-br from-[#edf9ee] to-[#f6f8f6] p-8 text-center">
                                <i class="fas fa-history text-4xl text-slate-400"></i>
                                <p class="mt-4 text-sm text-slate-500">Belum ada riwayat top up</p>
                            </div>

                            <div v-else class="space-y-3">
                                <div
                                    v-for="(topup, idx) in sortedTopUps"
                                    :key="topup.id"
                                    data-aos="fade-up"
                                    :data-aos-delay="idx * 50"
                                    class="group rounded-[1.5rem] bg-white p-5 shadow-sm ring-1 ring-slate-100 transition duration-300 hover:shadow-lg hover:shadow-green-900/10 hover:-translate-y-1"
                                >
                                    <div class="flex items-center justify-between gap-4">
                                        <!-- Left -->
                                        <div class="flex items-center gap-4 flex-1 min-w-0">
                                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full" :class="topup.status === 'approved' ? 'bg-green-100' : topup.status === 'pending' ? 'bg-amber-100' : 'bg-red-100'">
                                                <i v-if="topup.status === 'approved'" class="fas fa-check text-green-600"></i>
                                                <i v-else-if="topup.status === 'pending'" class="fas fa-hourglass-half text-amber-600"></i>
                                                <i v-else class="fas fa-times text-red-600"></i>
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <p class="font-black text-slate-900">Rp {{ Number(topup.amount).toLocaleString('id-ID') }}</p>
                                                <p class="mt-0.5 text-xs text-slate-500">{{ new Date(topup.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
                                            </div>
                                        </div>

                                        <!-- Status Badge -->
                                        <div>
                                            <span v-if="topup.status === 'approved'" class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                                <i class="fas fa-check-circle"></i>Berhasil
                                            </span>
                                            <span v-else-if="topup.status === 'pending'" class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                                <i class="fas fa-clock"></i>Menunggu
                                            </span>
                                            <span v-else class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                                <i class="fas fa-times-circle"></i>Ditolak
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>