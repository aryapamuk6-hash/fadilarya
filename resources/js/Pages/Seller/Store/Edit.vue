<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
    store: Object,
})

const form = useForm({
    name: props.store?.name ?? '',
    description: props.store?.description ?? '',
    address: props.store?.address ?? '',
    logo: null,
    banner: null,
})

function submit() {
    form.post(
        route('seller.store.update'),
        {
            forceFormData: true,
        }
    )
}
</script>

<template>
    <Head title="Profil Toko" />

    <AuthenticatedLayout>

        <div
            class="max-w-5xl mx-auto py-8"
        >

            <div
                class="bg-white rounded-2xl shadow p-8"
            >

                <h1
                    class="text-3xl font-bold mb-6"
                >
                    Profil Toko
                </h1>

                <form
                    @submit.prevent="submit"
                    class="space-y-6"
                >

                    <div>

                        <label
                            class="block font-semibold mb-2"
                        >
                            Nama Toko
                        </label>

                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full border rounded-lg px-4 py-3"
                        >

                    </div>

                    <div>

                        <label
                            class="block font-semibold mb-2"
                        >
                            Deskripsi
                        </label>

                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full border rounded-lg px-4 py-3"
                        />

                    </div>

                    <div>

                        <label
                            class="block font-semibold mb-2"
                        >
                            Alamat
                        </label>

                        <textarea
                            v-model="form.address"
                            rows="3"
                            class="w-full border rounded-lg px-4 py-3"
                        />

                    </div>

                    <div>

                        <label
                            class="block font-semibold mb-2"
                        >
                            Logo Toko
                        </label>

                        <input
                            type="file"
                            @input="
                                form.logo =
                                $event.target.files[0]
                            "
                        >

                        <img
                            v-if="store.logo"
                            :src="'/storage/' + store.logo"
                            class="w-28 h-28 object-cover rounded-full mt-4"
                        >

                    </div>

                    <div>

                        <label
                            class="block font-semibold mb-2"
                        >
                            Banner Toko
                        </label>

                        <input
                            type="file"
                            @input="
                                form.banner =
                                $event.target.files[0]
                            "
                        >

                        <img
                            v-if="store.banner"
                            :src="'/storage/' + store.banner"
                            class="w-full h-48 object-cover rounded-xl mt-4"
                        >

                    </div>

                    <button
                        class="bg-green-600 text-white px-6 py-3 rounded-lg"
                    >
                        Simpan Perubahan
                    </button>

                </form>

            </div>

        </div>

    </AuthenticatedLayout>
</template>