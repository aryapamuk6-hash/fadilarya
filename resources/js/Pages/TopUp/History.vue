<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    topUps: Array,
})

const sortedTopUps = computed(() => {
    return [...props.topUps].sort((a, b) => a.id - b.id)
})
</script>

<template>
    <Head title="Riwayat Top Up" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Dompet Digital</p>
                    <h2 class="mt-2 text-2xl font-black text-[#0b2617] md:text-3xl">
                        Riwayat Top Up
                    </h2>
                </div>
                <div class="rounded-full bg-white px-4 py-2 text-sm font-bold text-[#0c7c43] shadow-sm ring-1 ring-green-100">
                    <i class="fas fa-history mr-2"></i>{{ topUps.length }} transaksi
                </div>
            </div>
        </template>

        <div class="min-h-screen overflow-x-hidden bg-[#f6f8f6]">
            <div class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
                <div v-if="topUps.length === 0" class="rounded-[2rem] border-2 border-dashed border-green-200 bg-gradient-to-br from-[#edf9ee] to-[#f6f8f6] p-12 text-center" data-aos="fade-up">
                    <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-white shadow-lg ring-1 ring-green-100">
                        <i class="fas fa-history text-4xl text-slate-400"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900">Belum ada riwayat top up</h3>
                    <p class="mt-3 text-slate-500">Mulai top up saldo Anda sekarang!</p>
                    <a href="#" class="btn-shine mt-6 inline-flex items-center gap-2 rounded-2xl bg-[#0c7c43] px-6 py-3 font-bold text-white shadow-lg transition hover:-translate-y-1">
                        <i class="fas fa-arrow-up-right"></i>
                        Top Up Sekarang
                    </a>
                </div>

                <div v-else class="rounded-[1.5rem] bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
                    <!-- Table Header for Desktop -->
                    <div class="hidden md:grid md:grid-cols-4 gap-4 bg-gradient-to-r from-[#edf9ee] via-white to-[#f3faf5] border-b border-slate-100 p-5 font-black text-sm uppercase tracking-widest text-slate-600">
                        <div><i class="fas fa-calendar mr-2"></i>Tanggal</div>
                        <div><i class="fas fa-money-bill mr-2"></i>Nominal</div>
                        <div><i class="fas fa-info-circle mr-2"></i>Status</div>
                        <div><i class="fas fa-clock mr-2"></i>Waktu</div>
                    </div>

                    <!-- Table Body -->
                    <div class="divide-y divide-slate-100">
                        <div
                            v-for="(topup, idx) in sortedTopUps"
                            :key="topup.id"
                            data-aos="fade-up"
                            :data-aos-delay="idx * 30"
                            class="group hover:bg-[#f9faf9] transition duration-300 p-5 grid gap-4 md:grid-cols-4 md:items-center"
                        >
                            <!-- Date -->
                            <div>
                                <p class="md:hidden text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Tanggal</p>
                                <p class="font-bold text-slate-900">
                                    {{ new Date(topup.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">{{ new Date(topup.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) }}</p>
                            </div>

                            <!-- Amount -->
                            <div>
                                <p class="md:hidden text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Nominal</p>
                                <p class="text-lg font-black text-[#0c7c43]">
                                    Rp {{ Number(topup.amount).toLocaleString('id-ID') }}
                                </p>
                            </div>

                            <!-- Status -->
                            <div>
                                <p class="md:hidden text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Status</p>
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

                            <!-- Time -->
                            <div>
                                <p class="md:hidden text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Waktu</p>
                                <p class="text-sm text-slate-600">
                                    {{ new Date(topup.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>