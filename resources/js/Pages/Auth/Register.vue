<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('register'))
}
</script>

<template>

    <Head title="Daftar - Lumira" />

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
                            Mulai perjalananmu
                        </p>

                        <h2 class="mt-5 max-w-xl text-5xl font-black leading-tight text-white">
                            Satu akun untuk
                            <span class="text-green-300">
                                memberi nilai baru.
                            </span>
                        </h2>

                        <p class="mt-6 max-w-lg text-lg leading-8 text-green-100/70">
                            Bergabung dengan Lumira dan mulai jual,
                            beli, serta gunakan kembali barang yang masih
                            memiliki nilai.
                        </p>


                        <div class="mt-8 space-y-4">

                            <div class="flex items-center gap-3 text-sm text-green-100">

                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-slate-300">
                                    ♻
                                </span>

                                Marketplace barang reusable

                            </div>


                            <div class="flex items-center gap-3 text-sm text-green-100">

                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-slate-300">
                                    +
                                </span>

                                Bisa menjadi seller

                            </div>


                            <div class="flex items-center gap-3 text-sm text-green-100">

                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-slate-300">
                                    ♧
                                </span>

                                Ikut menjaga lingkungan

                            </div>

                        </div>

                    </div>


                    <div class="mt-auto text-sm text-green-200/60">
                        © {{ new Date().getFullYear() }} LUMIRA. Dibuat oleh Tim IGM.
                    </div>

                </div>

            </div>


            <!-- ================= -->
            <!-- RIGHT -->
            <!-- ================= -->

            <div class="flex items-center justify-center px-6 py-10">

                <div class="w-full max-w-md">

                    <!-- MOBILE LOGO -->

                    <div class="mb-8 lg:hidden">

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


                    <!-- CARD -->

                    <div class="rounded-[2rem] border border-slate-100 bg-white p-7 shadow-xl shadow-slate-200/50 sm:p-9">

                        <div class="mb-7">

                            <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-500">
                                +
                            </div>

                            <h2 class="text-3xl font-black text-slate-950">
                                Buat akun Lumira
                            </h2>

                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Daftar dan mulai gunakan Lumira.
                            </p>

                        </div>


                        <form @submit.prevent="submit" class="space-y-4">

                            <!-- NAME -->

                            <div>

                                <label
                                    for="name"
                                    class="mb-2 block text-sm font-bold text-slate-700"
                                >
                                    Nama
                                </label>

                                <input
                                    id="name"
                                    type="text"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Nama lengkap"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm outline-none transition focus:border-green-700 focus:bg-white focus:ring-4 focus:ring-green-100"
                                />

                                <p
                                    v-if="form.errors.name"
                                    class="mt-2 text-sm text-red-500"
                                >
                                    {{ form.errors.name }}
                                </p>

                            </div>


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

                                <label
                                    for="password"
                                    class="mb-2 block text-sm font-bold text-slate-700"
                                >
                                    Password
                                </label>

                                <input
                                    id="password"
                                    type="password"
                                    v-model="form.password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Buat password"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm outline-none transition focus:border-green-700 focus:bg-white focus:ring-4 focus:ring-green-100"
                                />

                                <p
                                    v-if="form.errors.password"
                                    class="mt-2 text-sm text-red-500"
                                >
                                    {{ form.errors.password }}
                                </p>

                            </div>


                            <!-- CONFIRM PASSWORD -->

                            <div>

                                <label
                                    for="password_confirmation"
                                    class="mb-2 block text-sm font-bold text-slate-700"
                                >
                                    Konfirmasi Password
                                </label>

                                <input
                                    id="password_confirmation"
                                    type="password"
                                    v-model="form.password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Ulangi password"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm outline-none transition focus:border-green-700 focus:bg-white focus:ring-4 focus:ring-green-100"
                                />

                                <p
                                    v-if="form.errors.password_confirmation"
                                    class="mt-2 text-sm text-red-500"
                                >
                                    {{ form.errors.password_confirmation }}
                                </p>

                            </div>


                            <!-- BUTTON -->

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="mt-2 w-full rounded-xl bg-[#14532d] px-5 py-3.5 font-bold text-white shadow-lg shadow-green-900/20 transition hover:bg-[#166534] disabled:cursor-not-allowed disabled:opacity-60"
                            >

                                <span v-if="!form.processing">
                                    Buat Akun
                                </span>

                                <span v-else>
                                    Membuat akun...
                                </span>

                            </button>

                        </form>


                        <!-- LOGIN -->

                        <div class="mt-7 border-t border-slate-100 pt-6 text-center">

                            <p class="text-sm text-slate-500">

                                Sudah punya akun?

                                <Link
                                    :href="route('login')"
                                    class="font-bold text-[#166534] hover:underline"
                                >
                                    Masuk sekarang
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