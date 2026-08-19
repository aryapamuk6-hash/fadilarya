<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    status: { type: String, default: '' },
})

const form = useForm({ email: '' })

const submit = () => {
    form.post(route('password.email'))
}
</script>

<template>
    <Head title="Lupa Password - Lumira" />

    <div class="min-h-screen bg-[#f7faf8] px-4 py-6 sm:px-6 sm:py-10">
        <div class="mx-auto flex min-h-[calc(100vh-3rem)] max-w-6xl items-center justify-center">
            <div class="grid w-full overflow-hidden rounded-[2rem] border border-slate-100 bg-white shadow-2xl shadow-slate-200/60 lg:grid-cols-[0.9fr_1.1fr]">
                <div class="relative hidden overflow-hidden bg-[#14532d] p-10 lg:flex lg:flex-col lg:justify-between xl:p-14">
                    <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-green-400/10"></div>
                    <div class="relative z-10">
                        <Link href="/" class="flex items-center gap-3"><img src="/images/lumira.png" alt="Lumira Logo" class="h-12 w-12" /><div><h1 class="text-2xl font-black text-white">LUMIRA</h1><p class="text-[9px] font-bold uppercase tracking-[0.2em] text-green-200">Layanan Ulang Material Ramah</p></div></Link>
                        <p class="mt-20 text-sm font-bold uppercase tracking-[0.25em] text-green-300">Pulihkan akses</p>
                        <h2 class="mt-5 text-5xl font-black leading-tight text-white">Kembali ke perjalanan <span class="text-green-300">Lumira.</span></h2>
                        <p class="mt-6 max-w-md text-lg leading-8 text-green-100/70">Masukkan email akunmu. Kami akan mengirimkan tautan untuk membuat password baru.</p>
                    </div>
                    <p class="relative z-10 text-sm text-green-200/60">© {{ new Date().getFullYear() }} LUMIRA. Dibuat oleh Tim IGM.</p>
                </div>

                <div class="flex items-center px-6 py-10 sm:px-10 lg:px-14">
                    <div class="w-full max-w-md">
                        <div class="mb-8 lg:hidden"><Link href="/" class="flex items-center gap-3"><img src="/images/lumira.png" alt="Lumira Logo" class="h-11 w-11" /><div><h1 class="text-xl font-black text-[#14532d]">LUMIRA</h1><p class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Layanan Ulang Material Ramah</p></div></Link></div>
                        <Link href="/login" class="inline-flex min-h-10 items-center gap-2 text-sm font-bold text-[#166534] hover:underline"><i class="fas fa-arrow-left"></i>Kembali ke login</Link>
                        <div class="mt-7 rounded-[1.75rem] bg-slate-50 p-6 ring-1 ring-slate-100 sm:p-8">
                            <div class="mb-7"><div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#edf9ee] text-xl text-[#0c7c43]"><i class="fas fa-key"></i></div><h2 class="text-3xl font-black text-slate-950">Lupa password?</h2><p class="mt-2 text-sm leading-6 text-slate-500">Masukkan email akunmu untuk menerima tautan reset password.</p></div>
                            <div v-if="status || form.recentlySuccessful" class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">{{ status || 'Tautan reset password berhasil dikirim.' }}</div>
                            <form @submit.prevent="submit" class="space-y-5">
                                <div><label for="email" class="mb-2 block text-sm font-bold text-slate-700">Email</label><input id="email" v-model="form.email" type="email" required autofocus autocomplete="username" placeholder="nama@email.com" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm outline-none transition focus:border-green-700 focus:ring-4 focus:ring-green-100" /><p v-if="form.errors.email" class="mt-2 text-sm text-red-500">{{ form.errors.email }}</p></div>
                                <button type="submit" :disabled="form.processing" class="w-full rounded-xl bg-[#14532d] px-5 py-3.5 font-bold text-white shadow-lg shadow-green-900/20 transition hover:bg-[#166534] disabled:cursor-not-allowed disabled:opacity-60"><i class="fas fa-paper-plane mr-2"></i>{{ form.processing ? 'Mengirim...' : 'Kirim Link Reset' }}</button>
                            </form>
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
