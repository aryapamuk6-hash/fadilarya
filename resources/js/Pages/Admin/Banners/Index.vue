<script setup>
import { computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    banners: {
        type: Array,
        default: () => [],
    },
})

const bannerList = computed(() => props.banners ?? [])

const form = useForm({
    title: '',
    description: '',
    image: null,
    button_text: '',
    button_link: '',
})

const submit = () => {
    form.post(route('admin.banners.store'), {
        forceFormData: true,
        onSuccess: () => {
            form.reset()
            form.clearErrors()
        },
    })
}

const removeBanner = (id) => {
    if (confirm('Hapus banner ini?')) {
        router.delete(route('admin.banners.destroy', id))
    }
}

const toggleBanner = (id) => {
    router.patch(route('admin.banners.toggle', id))
}
</script>

<template>
    <Head title="Banner Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-600">CMS</p>
                    <h2 class="text-xl font-bold text-slate-800">Banner Management</h2>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-xl font-bold text-slate-800">Tambah Banner</h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <input v-model="form.title" type="text" placeholder="Judul Banner" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-700 outline-none transition focus:border-emerald-500 focus:bg-white" />
                        <input v-model="form.button_text" type="text" placeholder="Teks Tombol" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-700 outline-none transition focus:border-emerald-500 focus:bg-white" />
                    </div>

                    <textarea v-model="form.description" placeholder="Deskripsi banner" rows="4" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-700 outline-none transition focus:border-emerald-500 focus:bg-white" />
                    <input v-model="form.button_link" type="text" placeholder="/marketplace" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-700 outline-none transition focus:border-emerald-500 focus:bg-white" />
                    <input type="file" @change="form.image = $event.target.files[0]" class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:font-semibold file:text-white" />

                    <button type="submit" :disabled="form.processing" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-3 font-semibold text-white shadow-sm transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-60">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Banner' }}
                    </button>
                </form>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div v-for="banner in bannerList" :key="banner.id" class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <img v-if="banner.image" :src="`/storage/${banner.image}`" class="h-52 w-full object-cover" />

                    <div class="space-y-4 p-5">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-xl font-bold text-slate-800">{{ banner.title }}</h3>
                            <span :class="banner.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'" class="rounded-full px-2.5 py-1 text-xs font-semibold">
                                {{ banner.is_active ? 'Active' : 'Non Active' }}
                            </span>
                        </div>

                        <p class="text-sm leading-relaxed text-slate-600">{{ banner.description || 'Tidak ada deskripsi.' }}</p>
                        <div v-if="banner.button_link" class="text-sm text-emerald-600">Link: {{ banner.button_link }}</div>

                        <div class="flex flex-wrap gap-2 pt-2">
                            <button @click="toggleBanner(banner.id)" class="rounded-xl bg-amber-500 px-3 py-2 text-sm font-semibold text-white transition hover:bg-amber-600">
                                {{ banner.is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                            <button @click="removeBanner(banner.id)" class="rounded-xl bg-red-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
