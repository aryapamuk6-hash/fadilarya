<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const preview = ref(
    '/storage/' + props.product.image
)

function previewImage(event) {

    const file =
        event.target.files[0]

    if (!file) return

    form.image = file

    preview.value =
        URL.createObjectURL(file)
}

const props = defineProps({
    product: Object,
    categories: Array,
})

const form = useForm({
    name: props.product.name,
    category_id: props.product.category_id,
    description: props.product.description,
    price: props.product.price,
    stock: props.product.stock,
    status: props.product.status,
    image: null,
})

function submit() {

    form.post(
        route(
            'seller.products.update',
            props.product.id
        ),
        {
            _method: 'put',
            forceFormData: true,
        }
    )
}
</script>

<template>
    <Head title="Edit Produk" />

    <AuthenticatedLayout>

        <template #header>
            <h2 class="text-xl font-bold">
                Edit Produk
            </h2>
        </template>

        <div class="max-w-4xl mx-auto py-8">

            <div
                class="bg-white rounded-xl shadow p-6"
            >

                <form
                    @submit.prevent="submit"
                    class="space-y-5"
                >

                    <div>
                        <label class="block mb-2">
                            Nama Produk
                        </label>

                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full border rounded-lg px-4 py-2"
                        >
                    </div>

                    <div>
                        <label class="block mb-2">
                            Kategori
                        </label>

                        <select
                            v-model="form.category_id"
                            class="w-full border rounded-lg px-4 py-2"
                        >
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2">
                            Harga
                        </label>

                        <input
                            v-model="form.price"
                            type="number"
                            class="w-full border rounded-lg px-4 py-2"
                        >
                    </div>

                    <div>
                        <label class="block mb-2">
                            Stok
                        </label>

                        <input
                            v-model="form.stock"
                            type="number"
                            class="w-full border rounded-lg px-4 py-2"
                        >
                    </div>

                    <div>
                        <label class="block mb-2">
                            Status
                        </label>

                        <select
                            v-model="form.status"
                            class="w-full border rounded-lg px-4 py-2"
                        >
                            <option value="active">
                                Active
                            </option>

                            <option value="sold_out">
                                Sold Out
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2">
                            Deskripsi
                        </label>

                        <textarea
                            v-model="form.description"
                            rows="6"
                            class="w-full border rounded-lg px-4 py-2"
                        />
                    </div>

                    <div>

                        <label class="block mb-2">
                            Foto Saat Ini
                        </label>

                        <img
    :src="preview"
    class="w-40 rounded-lg mb-4"
/>

<input
    type="file"
    @change="previewImage"
/>

                    </div>

                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg"
                    >
                        Simpan Perubahan
                    </button>

                </form>

            </div>

        </div>

    </AuthenticatedLayout>
</template>
