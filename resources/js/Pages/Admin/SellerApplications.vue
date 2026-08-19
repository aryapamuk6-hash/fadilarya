<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    applications: Array,
})

const sortedApplications = computed(() => {
    return [...props.applications].sort((a, b) => a.id - b.id)
})

const approve = (id) => {
    router.post(route('admin.seller.approve', id))
}

const reject = (id) => {
    router.post(route('admin.seller.reject', id))
}

const statusClass = (status = '') => {
    const normalized = status.toLowerCase()

    if (normalized.includes('approved') || normalized.includes('disetujui')) {
        return 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200'
    }

    if (normalized.includes('rejected') || normalized.includes('ditolak')) {
        return 'bg-red-100 text-red-700 ring-1 ring-red-200'
    }

    return 'bg-amber-100 text-amber-700 ring-1 ring-amber-200'
}
</script>

<template>
    <Head title="Pengajuan Seller" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#0c7c43]">Admin</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Pengajuan Seller</h2>
                </div>
            </div>
        </template>

        <div class="p-8">
            <section>
                <div
                    v-for="item in sortedApplications"
                    v-if="sortedApplications && sortedApplications.length"
                    :key="item.id"
                    class="mb-4 rounded border bg-white p-5"
                >

                    <h3 class="font-bold text-lg">
                        {{ item.store_name }}
                    </h3>

                    <p>
                        Pemilik:
                        {{ item.user.name }}
                    </p>

                    <p>
                        Status:
                        {{ item.status }}
                    </p>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <button
                            v-if="!String(item.status).toLowerCase().includes('approved') && !String(item.status).toLowerCase().includes('disetujui') && !String(item.status).toLowerCase().includes('rejected') && !String(item.status).toLowerCase().includes('ditolak')"
                            @click="approve(item.id)"
                            class="rounded-xl bg-[#0c7c43] px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-green-900/15 transition hover:-translate-y-0.5 hover:bg-[#0b2617]"
                        >
                            <i class="fas fa-check mr-2"></i>Approve
                        </button>
                        <button
                            v-if="!String(item.status).toLowerCase().includes('approved') && !String(item.status).toLowerCase().includes('disetujui') && !String(item.status).toLowerCase().includes('rejected') && !String(item.status).toLowerCase().includes('ditolak')"
                            @click="reject(item.id)"
                            class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-red-900/15 transition hover:-translate-y-0.5 hover:bg-red-700"
                        >
                            <i class="fas fa-times mr-2"></i>Reject
                        </button>
                    </div>
                </div>

                <div v-else class="rounded-[1.5rem] border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-slate-500">
                    Belum ada pengajuan seller.
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>