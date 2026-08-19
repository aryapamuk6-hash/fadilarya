<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
    application: Object,
})

const form = useForm({
    store_name: '',
    description: '',
    phone: '',
    id_card: null,
    selfie: null,
})

const submit = () => {
    form.post(route('seller.apply.store'), {
        forceFormData: true,
    })
}
</script>

<template>
    <Head title="Daftar Seller" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#0c7c43]">Seller</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Daftar Menjadi Seller</h2>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-5xl py-8">
            <div v-if="application" class="mb-6 rounded-[1.5rem] border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 shadow-sm">
                <span class="font-bold">Status:</span>
                <span class="ml-2 inline-flex rounded-full bg-amber-200 px-2.5 py-1 text-xs font-bold uppercase tracking-[0.14em] text-amber-800">{{ application.status }}</span>
            </div>

            <form class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-slate-100 sm:p-8" @submit.prevent="submit">
                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#edf9ee] text-[#0c7c43]">
                        <i class="fas fa-store"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.22em] text-[#0c7c43]">Pendaftaran</p>
                        <h3 class="mt-1 text-2xl font-black text-slate-900">Informasi Toko</h3>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Nama Toko</label>
                        <input v-model="form.store_name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-700 outline-none transition focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100" placeholder="Masukkan nama toko Anda" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Deskripsi</label>
                        <textarea v-model="form.description" rows="5" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-700 outline-none transition focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100" placeholder="Jelaskan toko Anda" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">No HP</label>
                        <input v-model="form.phone" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-700 outline-none transition focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100" placeholder="Contoh: 0812xxxxxxxx" />
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">KTP</label>
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-3 text-sm text-slate-600">
                                <input type="file" @input="form.id_card = $event.target.files[0]" class="block w-full text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-[#0c7c43] file:px-4 file:py-2 file:text-sm file:font-bold file:text-white" />
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Selfie</label>
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-3 text-sm text-slate-600">
                                <input type="file" @input="form.selfie = $event.target.files[0]" class="block w-full text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-[#0c7c43] file:px-4 file:py-2 file:text-sm file:font-bold file:text-white" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button class="rounded-2xl bg-[#0c7c43] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-green-900/15 transition hover:-translate-y-0.5 hover:bg-[#0b2617]">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>