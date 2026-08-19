<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section class="rounded-[1.5rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
        <header class="mb-6">
            <div class="flex items-center gap-4 mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#0c7c43]/10 text-[#0c7c43]">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-900">
                        Informasi Profil
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Perbarui informasi profil dan email akun Anda.
                    </p>
                </div>
            </div>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="space-y-5"
        >
            <div>
                <label for="name" class="block text-sm font-black uppercase tracking-widest text-slate-600 mb-2">Nama Lengkap</label>

                <input
                    id="name"
                    type="text"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#0c7c43] focus:ring-2 focus:ring-green-100"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <span v-if="form.errors.name" class="mt-2 block text-sm font-bold text-red-600">
                    {{ form.errors.name }}
                </span>
            </div>

            <div>
                <label for="email" class="block text-sm font-black uppercase tracking-widest text-slate-600 mb-2">Email</label>

                <input
                    id="email"
                    type="email"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#0c7c43] focus:ring-2 focus:ring-green-100"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <span v-if="form.errors.email" class="mt-2 block text-sm font-bold text-red-600">
                    {{ form.errors.email }}
                </span>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="rounded-xl bg-amber-50 border border-amber-200 p-4">
                <p class="text-sm text-amber-900 font-bold mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Email Anda belum diverifikasi.
                </p>
                <Link
                    :href="route('verification.send')"
                    method="post"
                    as="button"
                    class="text-sm font-bold text-amber-700 hover:text-amber-900 underline transition"
                >
                    Klik di sini untuk mengirim ulang email verifikasi.
                </Link>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-3 text-sm font-bold text-green-600"
                >
                    <i class="fas fa-check-circle mr-2"></i>Email verifikasi baru telah dikirim ke alamat email Anda.
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="btn-shine inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#0c7c43] to-[#0b2617] px-6 py-2.5 font-black text-white shadow-lg shadow-green-900/20 transition hover:-translate-y-0.5 hover:shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="fas fa-save"></i>
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                </button>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm font-bold text-green-600">
                        <i class="fas fa-check-circle mr-1"></i>Tersimpan.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
