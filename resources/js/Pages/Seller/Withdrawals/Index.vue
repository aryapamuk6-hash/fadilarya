<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
    balance: Number,
    withdrawals: Array,
})

const form = useForm({
    amount: '',
    bank_name: '',
    account_number: '',
    account_name: '',
})

const formatRupiah = (value) =>
    Number(value || 0).toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    })

const submit = () => {
    form.post(route('seller.withdrawals.store'))
}
</script>

<template>
    <Head title="Withdrawal Seller" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-bold text-slate-800">Withdrawal Seller</h2>
        </template>

        <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-6 overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-600 to-green-500 p-6 text-white shadow-lg">
                <p class="text-sm uppercase tracking-[0.24em] text-emerald-100">Saldo tersedia</p>
                <h3 class="mt-3 text-3xl font-bold">{{ formatRupiah(balance) }}</h3>
                <p class="mt-2 text-sm text-emerald-50">Komisi Lumira otomatis dipotong saat pengajuan withdrawal.</p>
            </div>

            <div class="mb-6 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="mb-4 text-lg font-bold text-slate-800">Ajukan Withdrawal</h3>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Jumlah Withdrawal</label>
                            <input v-model="form.amount" type="number" min="10000" placeholder="Contoh: 500000" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" />
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Nama Bank</label>
                                <input v-model="form.bank_name" placeholder="BRI / BCA / Mandiri" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" />
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Nomor Rekening</label>
                                <input v-model="form.account_number" placeholder="1234567890" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Nama Pemilik Rekening</label>
                            <input v-model="form.account_name" placeholder="Nama pemilik rekening" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" />
                        </div>

                        <div class="rounded-xl bg-amber-50 p-3 text-sm text-amber-800 ring-1 ring-amber-200">
                            Besaran komisi Lumira mengikuti aturan: Rp 2.000 untuk total di bawah Rp 10.000 dan Rp 10.000 untuk total di atas Rp 11.000.
                        </div>

                        <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                            Ajukan Withdrawal
                        </button>
                    </form>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="mb-4 text-lg font-bold text-slate-800">Struk & Bukti</h3>
                    <div class="space-y-3 text-sm text-slate-600">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="font-semibold text-slate-700">Aturan komisi</p>
                            <p class="mt-2">- Di bawah Rp 10.000: Rp 2.000</p>
                            <p>- Di atas Rp 11.000: Rp 10.000</p>
                        </div>
                        <div class="rounded-xl bg-emerald-50 p-4 text-emerald-800 ring-1 ring-emerald-200">
                            <p class="font-semibold">Contoh payout</p>
                            <p class="mt-2">Dana penarikan yang diterima = total withdrawal - komisi Lumira</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="mb-4 text-lg font-bold text-slate-800">Riwayat Withdrawal</h3>

                <div v-if="!withdrawals?.length" class="rounded-xl bg-slate-50 p-6 text-center text-sm text-slate-500">
                    Belum ada riwayat withdrawal.
                </div>

                <div v-else class="space-y-3">
                    <div v-for="item in withdrawals" :key="item.id" class="rounded-xl border border-slate-200 p-4">
                        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-sm text-slate-500">Jumlah</p>
                                <p class="text-lg font-bold text-slate-800">{{ formatRupiah(item.amount) }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-slate-500">Transfer bersih</p>
                                <p class="text-base font-semibold text-green-600">{{ formatRupiah(item.net_amount ?? item.amount) }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-slate-500">Status</p>
                                <span v-if="item.status === 'pending'" class="inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700">Pending</span>
                                <span v-else-if="item.status === 'approved'" class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Approved</span>
                                <span v-else class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">Rejected</span>
                            </div>
                        </div>

                        <div class="mt-3 grid gap-3 md:grid-cols-2">
                            <div class="rounded-lg bg-slate-50 p-3 text-sm text-slate-600">
                                <p class="font-semibold text-slate-700">Bank</p>
                                <p class="mt-1">{{ item.bank_name }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-3 text-sm text-slate-600">
                                <p class="font-semibold text-slate-700">Rekening</p>
                                <p class="mt-1">{{ item.account_name }} / {{ item.account_number }}</p>
                            </div>
                        </div>

                        <div v-if="item.proof" class="mt-3 rounded-lg bg-emerald-50 p-3 text-sm text-emerald-800 ring-1 ring-emerald-200 whitespace-pre-line">
                            {{ item.proof }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
