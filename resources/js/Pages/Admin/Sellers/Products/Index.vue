<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    products: Array,
})

const sortedProducts = computed(() => {
    return [...props.products].sort((a, b) => a.id - b.id)
})

function destroyProduct(id) {

    if (
        !confirm(
            'Yakin ingin menghapus produk ini?'
        )
    ) {
        return
    }

    router.post(route('admin.products.delete', id), {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Produk Saya" />

    <AuthenticatedLayout>

        <template #header>
            <div
                class="flex justify-between items-center"
            >
                <h2
                    class="text-xl font-bold"
                >
                    Produk Saya
                </h2>

                <Link
                    :href="
                        route(
                            'seller.products.create'
                        )
                    "
                    class="bg-green-600 text-white px-4 py-2 rounded-lg"
                >
                    + Tambah Produk
                </Link>
            </div>
        </template>

        <div
            class="max-w-7xl mx-auto py-8"
        >

            <!-- Statistik -->

            <div
                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6"
            >

                <div
                    class="bg-white p-5 rounded-xl shadow"
                >
                    <p
                        class="text-gray-500"
                    >
                        Total Produk
                    </p>

                    <h3
                        class="text-3xl font-bold mt-2"
                    >
                        {{ products.length }}
                    </h3>
                </div>

                <div
                    class="bg-white p-5 rounded-xl shadow"
                >
                    <p
                        class="text-gray-500"
                    >
                        Produk Aktif
                    </p>

                    <h3
                        class="text-3xl font-bold text-green-600 mt-2"
                    >
                        {{
                            products.filter(
                                p =>
                                    p.status ===
                                    'active'
                            ).length
                        }}
                    </h3>
                </div>

                <div
                    class="bg-white p-5 rounded-xl shadow"
                >
                    <p
                        class="text-gray-500"
                    >
                        Sold Out
                    </p>

                    <h3
                        class="text-3xl font-bold text-red-600 mt-2"
                    >
                        {{
                            products.filter(
                                p =>
                                    p.status ===
                                    'sold_out'
                            ).length
                        }}
                    </h3>
                </div>

                <div
                    class="bg-white p-5 rounded-xl shadow"
                >
                    <p
                        class="text-gray-500"
                    >
                        Total Terjual
                    </p>

                    <h3
                        class="text-3xl font-bold text-blue-600 mt-2"
                    >
                        {{
                            products.reduce(
                                (
                                    total,
                                    product
                                ) =>
                                    total +
                                    product.sold_count,
                                0
                            )
                        }}
                    </h3>
                </div>

            </div>

            <!-- Table -->

            <div
                class="bg-white rounded-xl shadow overflow-hidden"
            >

                <div
                    v-if="
                        products.length === 0
                    "
                    class="p-10 text-center text-gray-500"
                >
                    Belum ada produk.
                </div>

                <div
                    v-else
                    class="overflow-x-auto"
                >

                    <table
                        class="w-full"
                    >

                        <thead
                            class="bg-gray-100"
                        >
                            <tr>

                                <th
                                    class="p-4 text-left"
                                >
                                    Gambar
                                </th>

                                <th
                                    class="p-4 text-left"
                                >
                                    Produk
                                </th>

                                <th
                                    class="p-4 text-left"
                                >
                                    Kategori
                                </th>

                                <th
                                    class="p-4 text-left"
                                >
                                    Harga
                                </th>

                                <th
                                    class="p-4 text-left"
                                >
                                    Stok
                                </th>

                                <th
                                    class="p-4 text-left"
                                >
                                    Terjual
                                </th>

                                <th
                                    class="p-4 text-left"
                                >
                                    Status
                                </th>

                                <th
                                    class="p-4 text-left"
                                >
                                    Aksi
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            <tr
                                v-for="
                                    product
                                    in sortedProducts
                                "
                                :key="
                                    product.id
                                "
                                class="border-t hover:bg-gray-50"
                            >

                                <td
                                    class="p-4"
                                >

                                    <img
                                        :src="
                                            '/storage/' +
                                            product.image
                                        "
                                        class="w-16 h-16 rounded object-cover"
                                    >

                                </td>

                                <td
                                    class="p-4"
                                >

                                    <div
                                        class="font-semibold"
                                    >
                                        {{
                                            product.name
                                        }}
                                    </div>

                                </td>

                                <td
                                    class="p-4"
                                >
                                    {{
                                        product
                                            .category
                                            ?.name
                                    }}
                                </td>

                                <td
                                    class="p-4"
                                >
                                    Rp
                                    {{
                                        Number(
                                            product.price
                                        ).toLocaleString(
                                            'id-ID'
                                        )
                                    }}
                                </td>

                                <td
                                    class="p-4"
                                >
                                    {{
                                        product.stock
                                    }}
                                </td>

                                <td
                                    class="p-4"
                                >
                                    {{
                                        product.sold_count
                                    }}
                                </td>

                                <td
                                    class="p-4"
                                >

                                    <span
                                        v-if="
                                            product.status ===
                                            'active'
                                        "
                                        class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm"
                                    >
                                        Active
                                    </span>

                                    <span
                                        v-else
                                        class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm"
                                    >
                                        Sold Out
                                    </span>

                                </td>

                                <td
                                    class="p-4"
                                >

                                    <div
                                        class="flex gap-2"
                                    >

                                        <Link
                                            :href="
                                                route(
                                                    'seller.products.edit',
                                                    product.id
                                                )
                                            "
                                            class="bg-blue-600 text-white px-3 py-2 rounded"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            @click="
                                                destroyProduct(
                                                    product.id
                                                )
                                            "
                                            class="bg-red-600 text-white px-3 py-2 rounded"
                                        >
                                            Hapus
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </AuthenticatedLayout>
</template>

