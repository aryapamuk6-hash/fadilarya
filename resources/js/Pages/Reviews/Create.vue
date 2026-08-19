<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
    order: Object
})

const form = useForm({
    rating: 5,
    comment: ''
})

function submit() {
    form.post(
        route(
            'reviews.store',
            props.order.id
        )
    )
}
</script>

<template>
    <Head title="Review Toko" />

    <AuthenticatedLayout>

        <div class="max-w-3xl mx-auto p-8">

            <div class="bg-white p-6 rounded shadow">

                <h1 class="text-2xl font-bold mb-6">
                    Beri Review
                </h1>

                <div class="mb-4">

                    <label>
                        Rating
                    </label>

                    <select
                        v-model="form.rating"
                        class="w-full border rounded p-3"
                    >
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>

                </div>

                <div class="mb-4">

                    <label>
                        Ulasan
                    </label>

                    <textarea
                        v-model="form.comment"
                        rows="5"
                        class="w-full border rounded p-3"
                    ></textarea>

                </div>

                <button
                    @click="submit"
                    class="bg-green-600 text-white px-5 py-3 rounded"
                >
                    Kirim Review
                </button>

            </div>

        </div>

    </AuthenticatedLayout>
</template>