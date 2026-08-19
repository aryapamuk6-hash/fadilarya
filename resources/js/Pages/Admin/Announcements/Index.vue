<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    announcements: Array,
})

const sortedAnnouncements = computed(() => {
    return [...props.announcements].sort((a, b) => a.id - b.id)
})

const form = useForm({
    title: '',
    content: '',
})

const submit = () => {
    form.post(route('admin.announcements.store'), {
        onSuccess: () => form.reset(),
    })
}
</script>

<template>
    <Head title="Pengumuman" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#0c7c43]">Komunikasi</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Pengumuman Admin</h2>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-5xl py-8">
            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-slate-100" data-aos="fade-up">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.22em] text-[#0c7c43]">Buat</p>
                        <h3 class="mt-2 text-2xl font-black text-slate-900">Buat Pengumuman</h3>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#edf9ee] text-[#0c7c43]">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <input
                        v-model="form.title"
                        type="text"
                        placeholder="Judul pengumuman"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100"
                    />

                    <textarea
                        v-model="form.content"
                        rows="5"
                        placeholder="Isi pengumuman..."
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100"
                    />

                    <button class="rounded-2xl bg-[#0c7c43] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-green-900/15 transition hover:-translate-y-0.5 hover:bg-[#0b2617]">
                        <i class="fas fa-paper-plane mr-2"></i>Simpan
                    </button>
                </form>
            </section>

            <section class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-slate-100" data-aos="fade-up" data-aos-delay="100">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.22em] text-[#0c7c43]">Terbaru</p>
                        <h3 class="mt-2 text-2xl font-black text-slate-900">Daftar Pengumuman</h3>
                    </div>
                </div>

                <div v-if="announcements && announcements.length" class="space-y-4">
                    <div v-for="announcement in sortedAnnouncements" :key="announcement.id" class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#edf9ee] text-[#0c7c43]">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900">{{ announcement.title }}</h4>
                            </div>
                        </div>
                        <p class="mt-4 text-sm leading-7 text-slate-600">{{ announcement.content }}</p>
                    </div>
                </div>

                <div v-else class="rounded-[1.5rem] border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-slate-500">
                    Belum ada pengumuman.
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>