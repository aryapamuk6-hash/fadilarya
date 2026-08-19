<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
    categories: { type: Array, default: () => [] },
})

const sortedCategories = computed(() => {
    return [...props.categories].sort((a, b) => a.id - b.id)
})

const previewImages = ref([])

const form = useForm({
    name: '',
    category_id: '',
    description: '',
    price: '',
    stock: '',
    image: null,
    images: [],
})

const formatCurrency = (value) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value || 0))

const handleFileChange = (event) => {
    const files = Array.from(event.target.files ?? [])
    if (!files.length) return

    form.image = files[0]
    form.images = files.slice(1)
    previewImages.value.forEach((url) => URL.revokeObjectURL(url))
    previewImages.value = files.map((file) => URL.createObjectURL(file))
}

function submit() {
    form.post(route('seller.products.store'), {
        forceFormData: true,
    })
}
</script>

<template>
    <Head title="Tambah Produk" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Seller tools</p>
                <h2 class="mt-2 text-2xl font-black text-[#0b2617] md:text-3xl">Tambah Produk Baru</h2>
            </div>
        </template>

        <div class="mx-auto max-w-6xl pb-8">
            <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="rounded-[2rem] bg-white p-6 shadow-[0_20px_60px_rgba(15,23,42,0.08)] ring-1 ring-slate-200 md:p-8">
                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Nama Produk</label>
                            <input v-model="form.name" type="text" placeholder="Contoh: Pot bunga dari kayu daur ulang" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-800 outline-none transition focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100" />
                            <p v-if="form.errors.name" class="mt-2 text-sm text-red-500">{{ form.errors.name }}</p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-bold text-slate-700">Kategori</label>
                                <select v-model="form.category_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-800 outline-none transition focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100">
                                    <option value="">Pilih Kategori</option>
                                    <option v-for="category in sortedCategories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.category_id" class="mt-2 text-sm text-red-500">{{ form.errors.category_id }}</p>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-slate-700">Stok</label>
                                <input v-model.number="form.stock" type="number" min="0" placeholder="10" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-800 outline-none transition focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100" />
                                <p v-if="form.errors.stock" class="mt-2 text-sm text-red-500">{{ form.errors.stock }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Deskripsi</label>
                            <textarea v-model="form.description" rows="5" placeholder="Jelaskan detail produk Anda..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-800 outline-none transition focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100"></textarea>
                            <p v-if="form.errors.description" class="mt-2 text-sm text-red-500">{{ form.errors.description }}</p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Harga</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-4 top-3.5 text-slate-500">Rp</span>
                                <input v-model="form.price" type="number" min="0" placeholder="150000" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-10 py-3 text-slate-800 outline-none transition focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100" />
                            </div>
                            <p v-if="form.errors.price" class="mt-2 text-sm text-red-500">{{ form.errors.price }}</p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Foto Produk</label>
                            <label class="flex cursor-pointer items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center transition hover:border-[#0c7c43] hover:bg-[#edf9ee]">
                                <input type="file" name="images[]" accept="image/*" multiple class="hidden" @change="handleFileChange" />
                                <div>
                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-white text-xl text-[#0c7c43] shadow-sm ring-1 ring-slate-200">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <p class="mt-3 text-sm font-bold text-slate-600">Klik untuk upload beberapa foto</p>
                                    <p class="mt-1 text-xs text-slate-500">Foto pertama menjadi foto utama. JPG, PNG, WebP</p>
                                </div>
                            </label>
                            <p v-if="form.errors.image" class="mt-2 text-sm text-red-500">{{ form.errors.image }}</p>
                            <p v-if="form.errors.images" class="mt-2 text-sm text-red-500">{{ form.errors.images }}</p>
                            <div v-if="previewImages.length" class="mt-4 grid grid-cols-3 gap-3">
                                <img v-for="(image, index) in previewImages" :key="image" :src="image" :alt="`Preview foto ${index + 1}`" class="h-24 w-full rounded-xl object-cover ring-1 ring-slate-200" />
                            </div>
                        </div>

                        <button type="submit" :disabled="form.processing" class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-[#0c7c43] to-[#0b2617] px-5 py-3.5 text-sm font-black text-white shadow-lg shadow-green-900/20 transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-70">
                            <i class="fas fa-save mr-2"></i>
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Produk' }}
                        </button>
                    </form>
                </div>

                <div class="rounded-[2rem] bg-gradient-to-br from-[#edf9ee] via-white to-[#f3faf5] p-6 shadow-[0_20px_60px_rgba(15,23,42,0.06)] ring-1 ring-green-100 md:p-8">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Preview</p>
                    <div class="mt-5 overflow-hidden rounded-[1.75rem] bg-white shadow-lg ring-1 ring-slate-200">
                        <img v-if="previewImages[0]" :src="previewImages[0]" alt="Preview produk" class="h-56 w-full object-cover" />
                        <div v-else class="flex h-56 items-center justify-center bg-slate-100 text-slate-400">
                            <i class="fas fa-image text-4xl"></i>
                        </div>
                        <div class="space-y-4 p-5">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">Barang daur ulang</p>
                                <h3 class="mt-2 text-2xl font-black text-slate-900">{{ form.name || 'Nama produk Anda' }}</h3>
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <p class="text-2xl font-black text-[#0c7c43]">{{ formatCurrency(form.price) }}</p>
                                <span class="rounded-full bg-[#edf9ee] px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#0c7c43]">
                                    {{ form.stock || 0 }} stok
                                </span>
                            </div>

                            <p class="text-sm leading-6 text-slate-500">
                                {{ form.description || 'Deskripsi produk akan muncul di sini agar pelanggan lebih memahami manfaat dan kualitas barang Anda.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
