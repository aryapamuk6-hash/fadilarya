<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
    vouchers: {
        type: Array,
        default: () => [],
    },
    users: {
        type: Array,
        default: () => [],
    },
})

const form = useForm({
    code: '',
    type: 'fixed',
    value: '',
    min_purchase: 0,
    scope: 'all',
    user_id: '',
    expired_at: '',
})

const formatRupiah = (value) => Number(value || 0).toLocaleString('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
})

const submit = () => {
    form.transform((data) => ({
        ...data,
        value: data.type === 'free_shipping' ? 0 : data.value,
    })).post(route('admin.vouchers.store'))
}
</script>

<template>
    <Head title="Voucher" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-bold text-gray-800">Voucher</h2>
        </template>

        <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800">Buat Voucher Baru</h3>

                    <form @submit.prevent="submit" class="mt-5 space-y-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Kode Voucher</label>
                            <input v-model="form.code" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" placeholder="LUMIRA10" />
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Jenis</label>
                                <select v-model="form.type" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200">
                                    <option value="fixed">Potongan Tetap</option>
                                    <option value="percentage">Diskon Persen</option>
                                    <option value="free_shipping">Gratis Ongkir</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Nilai Diskon</label>
                                <input v-model="form.value" :disabled="form.type === 'free_shipping'" type="number" min="0" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200 disabled:bg-slate-100" :placeholder="form.type === 'percentage' ? '10' : '10000'" />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Minimum Belanja</label>
                            <input v-model.number="form.min_purchase" type="number" min="0" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" placeholder="10000" />
                            <p class="mt-1 text-xs text-slate-500">Isi 0 jika voucher tidak memiliki minimum belanja.</p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Scope Voucher</label>
                            <select v-model="form.scope" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200">
                                <option value="all">Semua User</option>
                                <option value="user">User Tertentu</option>
                            </select>
                        </div>

                        <div v-if="form.scope === 'user'">
                            <label class="mb-1 block text-sm font-medium text-gray-700">Pilih User</label>
                            <select v-model="form.user_id" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200">
                                <option value="">-- pilih user --</option>
                                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }} ({{ user.email }})</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Tanggal Kedaluwarsa</label>
                            <input v-model="form.expired_at" type="date" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" />
                        </div>

                        <button type="submit" class="w-full rounded-xl bg-green-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-green-700">
                            Simpan Voucher
                        </button>
                    </form>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Voucher</h3>

                    <div v-if="!vouchers.length" class="mt-5 rounded-xl bg-slate-50 p-6 text-center text-sm text-slate-500">
                        Belum ada voucher yang dibuat.
                    </div>

                    <div v-else class="mt-5 space-y-3">
                        <div v-for="voucher in vouchers" :key="voucher.id" class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-lg font-bold text-slate-800">{{ voucher.code }}</p>
                                    <p class="text-xs text-slate-500">{{ voucher.scope === 'all' ? 'Semua User' : 'User Tertentu' }}</p>
                                </div>
                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                    {{ voucher.is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>

                            <div class="mt-3 grid gap-3 text-sm text-slate-600 md:grid-cols-2">
                                <div class="rounded-lg bg-slate-50 p-3">
                                    <p class="font-medium text-slate-700">Nilai</p>
                                    <p class="mt-1">{{ voucher.type === 'free_shipping' ? 'Gratis ongkir' : voucher.type === 'percentage' ? `${voucher.value}%` : formatRupiah(voucher.value) }}</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 p-3">
                                    <p class="font-medium text-slate-700">Minimum Belanja</p>
                                    <p class="mt-1">{{ voucher.min_purchase > 0 ? formatRupiah(voucher.min_purchase) : 'Tanpa minimum' }}</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 p-3">
                                    <p class="font-medium text-slate-700">Kedaluwarsa</p>
                                    <p class="mt-1">{{ voucher.expired_at ? new Date(voucher.expired_at).toLocaleDateString('id-ID') : 'Tidak ada' }}</p>
                                </div>
                            </div>

                            <p v-if="voucher.user" class="mt-3 text-sm text-slate-600">
                                Diberikan khusus untuk: {{ voucher.user.name }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

