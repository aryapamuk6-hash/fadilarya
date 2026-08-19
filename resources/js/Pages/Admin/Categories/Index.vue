<script setup>
import { ref, computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    categories: Array,
})

const sortedCategories = computed(() => {
    return [...props.categories].sort((a, b) => a.id - b.id)
})

const form = useForm({
    name: '',
})

const editingId = ref(null)

const submit = () => {
    if (editingId.value) {
        form.patch(route('admin.categories.update', editingId.value), {
            preserveScroll: true,
            onSuccess: () => cancelEdit(),
        })
        return
    }

    form.post(route('admin.categories.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    })
}

const editCategory = (category) => {
    editingId.value = category.id
    form.name = category.name
}

const cancelEdit = () => {
    editingId.value = null
    form.reset()
}

const destroyCategory = (id) => {
    if (!confirm('Hapus kategori ini?')) return
    router.delete(route('admin.categories.destroy', id))
}
</script>

<template>
    <Head title="Kelola Kategori" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#0c7c43]">Master Data</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Kelola Kategori</h2>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-7xl py-8">
            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-slate-100" data-aos="fade-up">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.22em] text-[#0c7c43]">Kategori</p>
                        <h3 class="mt-2 text-2xl font-black text-slate-900">{{ editingId ? 'Edit Kategori' : 'Tambah Kategori' }}</h3>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#edf9ee] text-[#0c7c43]">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>

                <form @submit.prevent="submit" class="flex flex-col gap-3 md:flex-row">
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Nama kategori"
                        class="flex-1 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100"
                    />

                    <button class="rounded-2xl bg-[#0c7c43] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-green-900/15 transition hover:-translate-y-0.5 hover:bg-[#0b2617]">
                        {{ editingId ? 'Simpan' : 'Tambah' }}
                    </button>

                    <button v-if="editingId" type="button" @click="cancelEdit" class="rounded-2xl bg-slate-200 px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-300">
                        Batal
                    </button>
                </form>
            </section>

            <section class="mt-8 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-slate-100" data-aos="fade-up" data-aos-delay="100">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.22em] text-[#0c7c43]">Daftar</p>
                        <h3 class="mt-2 text-2xl font-black text-slate-900">Daftar Kategori</h3>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-200 text-sm uppercase tracking-[0.14em] text-slate-400">
                                <th class="py-3 pr-4 font-semibold">Nama</th>
                                <th class="py-3 pr-4 font-semibold">Slug</th>
                                <th class="py-3 pr-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="category in sortedCategories" :key="category.id" class="border-b border-slate-100 text-sm text-slate-700 last:border-0">
                                <td class="py-4 pr-4 font-semibold text-slate-900">{{ category.name }}</td>
                                <td class="py-4 pr-4">{{ category.slug }}</td>
                                <td class="py-4 pr-4">
                                    <div class="flex flex-wrap gap-2">
                                        <button @click="editCategory(category)" class="rounded-xl bg-sky-600 px-3.5 py-2 text-xs font-bold text-white shadow-lg shadow-sky-900/15 transition hover:-translate-y-0.5 hover:bg-sky-700">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                        <button @click="destroyCategory(category.id)" class="rounded-xl bg-red-600 px-3.5 py-2 text-xs font-bold text-white shadow-lg shadow-red-900/15 transition hover:-translate-y-0.5 hover:bg-red-700">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
