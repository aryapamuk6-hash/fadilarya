<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    store: Object,
})

const sortedProducts = computed(() => {
    return [...props.store.products].sort((a, b) => a.id - b.id)
})

const sortedReviews = computed(() => {
    return [...props.store.reviews].sort((a, b) => a.id - b.id)
})
</script>

<template>
    <Head :title="store.name" />

    <AuthenticatedLayout>

        <div class="max-w-7xl mx-auto py-8">

            <!-- BANNER -->

            <div
                class="h-72 rounded-2xl overflow-hidden bg-gray-200"
            >
                <img
                    v-if="store.banner"
                    :src="'/storage/' + store.banner"
                    class="w-full h-full object-cover"
                >

                <div
                    v-else
                    class="w-full h-full flex items-center justify-center text-gray-500"
                >
                    Banner Toko
                </div>
            </div>

            <!-- PROFILE -->

            <div
                class="bg-white rounded-2xl shadow p-6 -mt-16 relative mx-6"
            >

                <div
                    class="flex flex-col md:flex-row gap-6 items-center"
                >

                    <img
                        v-if="store.logo"
                        :src="'/storage/' + store.logo"
                        class="w-32 h-32 rounded-full border-4 border-white object-cover shadow"
                    >

                    <div
                        v-else
                        class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center"
                    >
                        Logo
                    </div>

                    <div class="flex-1">

                        <h1
                            class="text-3xl font-bold"
                        >
                            {{ store.name }}
                        </h1>

                        <p
                            class="text-gray-500 mt-2"
                        >
                            {{ store.description }}
                        </p>

                        <div
                            class="flex flex-wrap gap-4 mt-4"
                        >

                            <span
                                class="bg-yellow-100 px-4 py-2 rounded-lg"
                            >
                                ⭐ {{ store.rating }}
                            </span>

                            <span
                                class="bg-green-100 px-4 py-2 rounded-lg"
                            >
                                🛒 {{ store.total_sales }} Penjualan
                            </span>

                            <span
                                class="bg-blue-100 px-4 py-2 rounded-lg"
                            >
                                💬 {{ store.reviews.length }} Review
                            </span>

                        </div>

                    </div>

                </div>

            </div>

            <!-- PRODUK TOKO -->

            <div class="mt-10">

                <h2
                    class="text-2xl font-bold mb-6"
                >
                    Produk Toko
                </h2>

                <div
                    class="grid grid-cols-1 md:grid-cols-4 gap-6"
                >

                    <div
                        v-for="product in sortedProducts"
                        :key="product.id"
                        class="bg-white rounded-xl shadow overflow-hidden"
                    >

                        <img
                            :src="'/storage/' + product.image"
                            class="w-full h-52 object-cover"
                        >

                        <div class="p-4">

                            <h3
                                class="font-bold text-lg"
                            >
                                {{ product.name }}
                            </h3>

                            <p
                                class="text-green-600 font-bold mt-2"
                            >
                                Rp {{
                                    Number(
                                        product.price
                                    ).toLocaleString(
                                        'id-ID'
                                    )
                                }}
                            </p>

                            <p
                                class="text-gray-500 text-sm mt-1"
                            >
                                Terjual:
                                {{ product.sold_count }}
                            </p>

                            <Link
                                :href="
                                    route(
                                        'marketplace.show',
                                        product.id
                                    )
                                "
                                class="block mt-4 bg-green-600 text-white text-center py-2 rounded-lg"
                            >
                                Lihat Produk
                            </Link>

                        </div>

                    </div>

                </div>

            </div>

            <!-- REVIEW -->

            <div
                class="mt-10 bg-white rounded-2xl shadow p-6"
            >

                <h2
                    class="text-2xl font-bold mb-6"
                >
                    Review Pembeli
                </h2>

                <div
                    v-if="store.reviews.length"
                    class="space-y-5"
                >

                    <div
                        v-for="review in sortedReviews"
                        :key="review.id"
                        class="border-b pb-4"
                    >

                        <h4
                            class="font-bold"
                        >
                            {{ review.user.name }}
                        </h4>

                        <p
                            class="text-yellow-500"
                        >
                            ⭐ {{ review.rating }}/5
                        </p>

                        <p
                            class="mt-2 text-gray-600"
                        >
                            {{ review.comment }}
                        </p>

                    </div>

                </div>

                <div
                    v-else
                    class="text-gray-500"
                >
                    Belum ada review.
                </div>

            </div>

        </div>

    </AuthenticatedLayout>
</template>