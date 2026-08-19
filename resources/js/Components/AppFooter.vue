<template>
    <footer class="relative border-t border-slate-200 bg-gradient-to-b from-slate-900 via-slate-950 to-black pt-10 pb-8 text-white">
        <!-- Decorative background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-0 h-96 w-96 rounded-full bg-[#0c7c43] opacity-5 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-emerald-500 opacity-5 blur-3xl"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-3 lg:grid-cols-4">
                <!-- Brand Section -->
                <div class="md:col-span-1">
                    <div class="space-y-3">
                        <h3 class="text-3xl font-black tracking-tight text-white">LUMIRA</h3>
                        <p class="text-sm font-semibold uppercase tracking-[0.15em] text-slate-400">Layanan Ulang Material Ramah</p>
                        <p class="text-xs text-slate-500">Platform marketplace terpercaya untuk perdagangan barang bekas berkualitas</p>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="mb-4 font-bold uppercase tracking-wide text-slate-100">Berbelanja</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="#" class="transition hover:text-emerald-400 hover:underline">Jelajahi Produk</a></li>
                        <li><a href="#" class="transition hover:text-emerald-400 hover:underline">Kategori</a></li>
                        <li><a href="#" class="transition hover:text-emerald-400 hover:underline">Seller Terbaik</a></li>
                        <li><a href="#" class="transition hover:text-emerald-400 hover:underline">Wishlist</a></li>
                    </ul>
                </div>

                <!-- Company Links -->
                <div>
                    <h4 class="mb-4 font-bold uppercase tracking-wide text-slate-100">Tentang</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="#" class="transition hover:text-emerald-400 hover:underline">Tentang Lumira</a></li>
                        <li><a href="#" class="transition hover:text-emerald-400 hover:underline">Kebijakan Privasi</a></li>
                        <li><a href="#" class="transition hover:text-emerald-400 hover:underline">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="transition hover:text-emerald-400 hover:underline">Hubungi Kami</a></li>
                    </ul>
                </div>

                <!-- Tagline Section -->
                <div class="md:col-span-1 md:col-end-4 lg:col-span-1 lg:col-end-5">
                    <div class="space-y-4">
                        <blockquote class="border-l-2 border-emerald-500 pl-4">
                            <p class="text-sm italic text-slate-300">"Berikan nilai baru pada yang tersisa"</p>
                        </blockquote>
                        <p class="text-xs text-slate-500">Mendukung ekonomi sirkular untuk masa depan yang lebih berkelanjutan</p>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="mt-6 border-t border-slate-800"></div>

            <!-- Bottom Section -->
            <div class="flex flex-col items-center justify-between gap-4 md:flex-row pt-4">
                <p class="text-center text-xs text-slate-500 md:text-left">
                    © 2026 <span class="font-bold text-white">LUMIRA</span>. Semua hak dilindungi. Dibuat oleh Tim IGM.
                </p>
                <div class="flex gap-6">
                    <a href="#" class="text-slate-400 transition hover:text-emerald-400">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    <a href="#" class="text-slate-400 transition hover:text-emerald-400">
                        <i class="fab fa-twitter text-lg"></i>
                    </a>
                    <a href="#" class="text-slate-400 transition hover:text-emerald-400">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    <a href="#" class="text-slate-400 transition hover:text-emerald-400">
                        <i class="fab fa-linkedin-in text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
// No logic needed for footer component
</script>
