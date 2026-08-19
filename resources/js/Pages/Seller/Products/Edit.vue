<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    product: { type: Object, required: true },
    categories: { type: Array, default: () => [] },
})

const sortedCategories = computed(() => [...props.categories].sort((a, b) => a.id - b.id))

const form = useForm({
    name: props.product.name ?? '',
    category_id: props.product.category_id ?? '',
    description: props.product.description ?? '',
    price: props.product.price ?? '',
    stock: props.product.stock ?? 0,
    status: props.product.status ?? 'active',
    image: null,
    images: [],
})

function submit() {
    form.transform((data) => ({ ...data, _method: 'put' })).post(route('seller.products.update', props.product.id), {
        forceFormData: true,
    })
}
</script>

<template>
    <Head title="Edit Produk" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Seller tools</p>
                    <h2 class="mt-2 text-2xl font-black text-[#0b2617] md:text-3xl">Edit Produk</h2>
                </div>
                <Link :href="route('seller.products.show', product.id)" class="text-sm font-bold text-[#0c7c43] hover:underline">Lihat detail produk</Link>
            </div>
        </template>

        <div class="mx-auto max-w-5xl">
            <form @submit.prevent="submit" class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="space-y-5 rounded-[2rem] bg-white p-6 shadow-[0_20px_60px_rgba(15,23,42,0.08)] ring-1 ring-slate-200 sm:p-8">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Nama Produk</label>
                        <input v-model="form.name" type="text" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-800 outline-none transition focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100" />
                        <p v-if="form.errors.name" class="mt-2 text-sm text-red-500">{{ form.errors.name }}</p>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Kategori</label>
                            <select v-model="form.category_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100">
                                <option value="">Pilih Kategori</option>
                                <option v-for="category in sortedCategories" :key="category.id" :value="category.id">{{ category.name }}</option>
                            </select>
                            <p v-if="form.errors.category_id" class="mt-2 text-sm text-red-500">{{ form.errors.category_id }}</p>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Stok</label>
                            <input v-model.number="form.stock" type="number" min="0" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100" />
                            <p v-if="form.errors.stock" class="mt-2 text-sm text-red-500">{{ form.errors.stock }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Harga</label>
                        <div class="relative"><span class="pointer-events-none absolute left-4 top-3.5 text-slate-500">Rp</span><input v-model="form.price" type="number" min="0" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-10 py-3 outline-none focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100" /></div>
                        <p v-if="form.errors.price" class="mt-2 text-sm text-red-500">{{ form.errors.price }}</p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Deskripsi</label>
                        <textarea v-model="form.description" rows="6" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100"></textarea>
                        <p v-if="form.errors.description" class="mt-2 text-sm text-red-500">{{ form.errors.description }}</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div><label class="mb-2 block text-sm font-bold text-slate-700">Ganti Foto Utama</label><input type="file" accept="image/*" @change="form.image = $event.target.files?.[0] ?? null" class="block w-full rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm" /><p v-if="form.errors.image" class="mt-2 text-sm text-red-500">{{ form.errors.image }}</p></div>
                        <div><label class="mb-2 block text-sm font-bold text-slate-700">Tambah Foto Galeri</label><input type="file" accept="image/*" multiple @change="form.images = Array.from($event.target.files ?? [])" class="block w-full rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm" /><p v-if="form.errors.images" class="mt-2 text-sm text-red-500">{{ form.errors.images }}</p></div>
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                        <button type="submit" :disabled="form.processing" class="inline-flex min-h-11 flex-1 items-center justify-center gap-2 rounded-2xl bg-[#0c7c43] px-5 py-3 text-sm font-black text-white shadow-lg shadow-green-900/20 transition hover:-translate-y-0.5 hover:bg-[#0b2617] disabled:opacity-60"><i class="fas fa-save"></i>{{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}</button>
                        <Link :href="route('seller.products.show', product.id)" class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">Batal</Link>
                    </div>
                </div>

                <aside class="rounded-[2rem] bg-gradient-to-br from-[#edf9ee] via-white to-[#f3faf5] p-6 shadow-sm ring-1 ring-green-100 sm:p-8">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Foto saat ini</p>
                    <div v-if="product.image" class="mt-5 overflow-hidden rounded-[1.5rem] shadow-lg ring-1 ring-green-100"><img :src="`/storage/${product.image}`" :alt="product.name" class="aspect-square w-full object-cover" /></div>
                    <div v-else class="mt-5 flex aspect-square items-center justify-center rounded-[1.5rem] bg-slate-100 text-slate-400"><i class="fas fa-image text-5xl"></i></div>
                    <div v-if="product.images?.length" class="mt-5 grid grid-cols-3 gap-2"><img v-for="image in product.images" :key="image.id" :src="`/storage/${image.image}`" alt="Foto galeri produk" class="aspect-square w-full rounded-xl object-cover ring-1 ring-slate-200" /></div>
                    <div class="mt-6 rounded-2xl bg-white/80 p-4 ring-1 ring-green-100"><p class="text-xs font-bold text-slate-500">Status stok</p><p class="mt-1 text-lg font-black" :class="form.stock > 0 ? 'text-emerald-700' : 'text-red-700'">{{ form.stock > 0 ? 'Siap dijual' : 'Habis' }}</p></div>
                </aside>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
