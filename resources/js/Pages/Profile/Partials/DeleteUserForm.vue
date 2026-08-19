<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="rounded-[1.5rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
        <header class="mb-6">
            <div class="flex items-center gap-4 mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-900">
                        Hapus Akun
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Setelah akun Anda dihapus, semua data akan dihapus secara permanen. Harap unduh data penting Anda sebelum menghapus akun.
                    </p>
                </div>
            </div>
        </header>

        <div>
            <button
                type="button"
                @click="confirmUserDeletion"
                class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-6 py-2.5 font-black text-white shadow-lg shadow-red-600/20 transition hover:-translate-y-0.5 hover:bg-red-700 hover:shadow-2xl"
            >
                <i class="fas fa-trash-alt"></i>
                Hapus Akun
            </button>
        </div>

        <!-- Deletion Confirmation Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition ease-in-out duration-200"
                enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out duration-200"
                leave-to-class="opacity-0"
            >
                <div v-if="confirmingUserDeletion" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                    <div class="rounded-[1.5rem] bg-white shadow-2xl ring-1 ring-slate-100 w-full max-w-md">
                        <div class="p-6">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                                </div>
                                <h2 class="text-xl font-black text-slate-900">
                                    Hapus Akun?
                                </h2>
                            </div>

                            <p class="mb-6 text-sm text-slate-600 leading-6">
                                Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini <strong class="text-red-600">tidak dapat dibatalkan</strong>. Semua data Anda akan dihapus secara permanen.
                            </p>

                            <div class="mb-6">
                                <label for="password" class="block text-sm font-bold text-slate-600 mb-2">Konfirmasi dengan memasukkan kata sandi Anda</label>

                                <input
                                    id="password"
                                    ref="passwordInput"
                                    v-model="form.password"
                                    type="password"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-red-600 focus:ring-2 focus:ring-red-100"
                                    placeholder="Kata sandi Anda"
                                    @keyup.enter="deleteUser"
                                />

                                <span v-if="form.errors.password" class="mt-2 block text-sm font-bold text-red-600">
                                    {{ form.errors.password }}
                                </span>
                            </div>

                            <div class="flex gap-3">
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 font-bold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
                                >
                                    Batal
                                </button>

                                <button
                                    type="button"
                                    @click="deleteUser"
                                    :disabled="form.processing"
                                    class="flex-1 rounded-xl bg-red-600 px-4 py-2.5 font-black text-white shadow-lg shadow-red-600/20 transition hover:-translate-y-0.5 hover:bg-red-700 hover:shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{ form.processing ? 'Menghapus...' : 'Ya, Hapus Akun' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </section>
</template>
