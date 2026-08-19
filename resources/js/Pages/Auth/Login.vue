<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
    canResetPassword: {
        type: Boolean,
    },

    status: {
        type: String,
    },
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>

    <Head title="Masuk - Lumira" />

    <div class="min-h-screen bg-[#f7faf8]">

        <div class="grid min-h-screen lg:grid-cols-2">

            <!-- ================= -->
            <!-- LEFT -->
            <!-- ================= -->

            <div class="relative hidden overflow-hidden bg-[#14532d] lg:flex">

                <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-green-400/10"></div>

                <div class="absolute -bottom-40 -left-32 h-[500px] w-[500px] rounded-full bg-emerald-400/10"></div>


                <div class="relative z-10 flex w-full flex-col justify-start p-12 xl:p-16 gap-8">

                    <Link href="/" class="flex items-center gap-3">

                        <img src="/images/lumira.png" alt="Lumira Logo" class="h-12 w-12">

                        <div>

                            <h1 class="text-2xl font-black text-white">
                                LUMIRA
                            </h1>

                            <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-green-200">
                                Layanan Ulang Material Ramah
                            </p>

                        </div>

                    </Link>


                    <div>

                        <p class="text-sm font-bold uppercase tracking-[0.25em] text-green-300">
                            Selamat datang kembali
                        </p>

                        <h2 class="mt-5 max-w-xl text-5xl font-black leading-tight text-white">
                            Mari lanjutkan
                            <span class="text-green-300">
                                perjalanan
                            </span>
                            bersama Lumira.
                        </h2>

                        <p class="mt-6 max-w-lg text-lg leading-8 text-green-100/70">
                            Temukan barang yang masih bernilai atau jual
                            barang yang ingin mendapatkan pemilik baru.
                        </p>

                    </div>


                    <div class="mt-auto text-sm text-green-200/60">
                        © {{ new Date().getFullYear() }} LUMIRA. Dibuat oleh Tim IGM.
                    </div>

                </div>

            </div>


            <!-- ================= -->
            <!-- RIGHT -->
            <!-- ================= -->

            <div class="flex items-center justify-center px-6 py-12">

                <div class="w-full max-w-md">

                    <!-- MOBILE LOGO -->

                    <div class="mb-10 lg:hidden">

                        <Link href="/" class="flex items-center gap-3">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#14532d] font-black text-white">
                                L
                            </div>

                            <div>

                                <h1 class="text-xl font-black text-[#14532d]">
                                    LUMIRA
                                </h1>

                                <p class="text-[8px] font-bold uppercase tracking-widest text-slate-400">
                                    Layanan Ulang Material Ramah
                                </p>

                            </div>

                        </Link>

                    </div>


                    <!-- FORM CARD -->

                    <div class="rounded-[2rem] border border-slate-100 bg-white p-7 shadow-xl shadow-slate-200/50 sm:p-9">

                        <div class="mb-8">

                            <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-500">
                                →
                            </div>

                            <h2 class="text-3xl font-black text-slate-950">
                                Masuk ke Lumira
                            </h2>

                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Masuk untuk melanjutkan aktivitas kamu.
                            </p>

                        </div>


                        <!-- STATUS -->

                        <div
                            v-if="status"
                            class="mb-5 rounded-xl bg-green-50 px-4 py-3 text-sm font-medium text-[#166534]"
                        >
                            {{ status }}
                        </div>


                        <form @submit.prevent="submit" class="space-y-5">

                            <!-- EMAIL -->

                            <div>

                                <label
                                    for="email"
                                    class="mb-2 block text-sm font-bold text-slate-700"
                                >
                                    Email
                                </label>

                                <input
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="nama@email.com"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm outline-none transition focus:border-green-700 focus:bg-white focus:ring-4 focus:ring-green-100"
                                />

                                <p
                                    v-if="form.errors.email"
                                    class="mt-2 text-sm text-red-500"
                                >
                                    {{ form.errors.email }}
                                </p>

                            </div>


                            <!-- PASSWORD -->

                            <div>

                                <div class="mb-2 flex items-center justify-between">

                                    <label
                                        for="password"
                                        class="block text-sm font-bold text-slate-700"
                                    >
                                        Password
                                    </label>

                                    <Link
                                        v-if="canResetPassword"
                                        :href="route('password.request')"
                                        class="text-xs font-bold text-[#166534] hover:underline"
                                    >
                                        Lupa password?
                                    </Link>

                                </div>

                                <input
                                    id="password"
                                    type="password"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan password"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm outline-none transition focus:border-green-700 focus:bg-white focus:ring-4 focus:ring-green-100"
                                />

                                <p
                                    v-if="form.errors.password"
                                    class="mt-2 text-sm text-red-500"
                                >
                                    {{ form.errors.password }}
                                </p>

                            </div>


                            <!-- REMEMBER -->

                            <label class="flex cursor-pointer items-center gap-3">

                                <input
                                    type="checkbox"
                                    v-model="form.remember"
                                    class="h-4 w-4 rounded border-slate-300 text-[#166534] focus:ring-green-600"
                                />

                                <span class="text-sm text-slate-500">
                                    Ingat saya
                                </span>

                            </label>


                            <!-- BUTTON -->

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full rounded-xl bg-[#14532d] px-5 py-3.5 font-bold text-white shadow-lg shadow-green-900/20 transition hover:bg-[#166534] disabled:cursor-not-allowed disabled:opacity-60"
                            >

                                <span v-if="!form.processing">
                                    Masuk ke Akun
                                </span>

                                <span v-else>
                                    Sedang masuk...
                                </span>

                            </button>

                        </form>


                        <!-- REGISTER -->

                        <div class="mt-7 border-t border-slate-100 pt-6 text-center">

                            <p class="text-sm text-slate-500">

                                Belum punya akun?

                                <Link
                                    :href="route('register')"
                                    class="font-bold text-[#166534] hover:underline"
                                >
                                    Daftar sekarang
                                </Link>

                            </p>

                        </div>

                        <p class="mt-6 text-center text-xs text-slate-400">
                            © {{ new Date().getFullYear() }} LUMIRA. Dibuat oleh Tim IGM.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</template>