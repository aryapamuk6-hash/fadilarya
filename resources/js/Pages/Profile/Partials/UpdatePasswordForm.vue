<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section class="rounded-[1.5rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
        <header class="mb-6">
            <div class="flex items-center gap-4 mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-lock"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-900">
                        Perbarui Kata Sandi
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Pastikan akun Anda menggunakan kata sandi yang panjang dan unik untuk keamanan maksimal.
                    </p>
                </div>
            </div>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-5">
            <div>
                <label for="current_password" class="block text-sm font-black uppercase tracking-widest text-slate-600 mb-2">Kata Sandi Sekarang</label>

                <input
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                    autocomplete="current-password"
                />

                <span v-if="form.errors.current_password" class="mt-2 block text-sm font-bold text-red-600">
                    {{ form.errors.current_password }}
                </span>
            </div>

            <div>
                <label for="password" class="block text-sm font-black uppercase tracking-widest text-slate-600 mb-2">Kata Sandi Baru</label>

                <input
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                    autocomplete="new-password"
                />

                <span v-if="form.errors.password" class="mt-2 block text-sm font-bold text-red-600">
                    {{ form.errors.password }}
                </span>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-black uppercase tracking-widest text-slate-600 mb-2">Konfirmasi Kata Sandi</label>

                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                    autocomplete="new-password"
                />

                <span v-if="form.errors.password_confirmation" class="mt-2 block text-sm font-bold text-red-600">
                    {{ form.errors.password_confirmation }}
                </span>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="btn-shine inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-2.5 font-black text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="fas fa-shield-alt"></i>
                    {{ form.processing ? 'Memperbarui...' : 'Perbarui Kata Sandi' }}
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
