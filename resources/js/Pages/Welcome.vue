<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed, onMounted, onUnmounted, ref } from 'vue'
import AOS from 'aos'
import AppFooter from '@/Components/AppFooter.vue'

const props = defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
    banners: {
        type: Array,
        default: () => [],
    },
})

const activeBanners = computed(() => props.banners ?? [])
const featuredBanner = computed(() => activeBanners.value[0] ?? null)
const secondaryBanners = computed(() => activeBanners.value.slice(1, 3))

const page = usePage()

const isLoggedIn = computed(() => !!page.props?.auth?.user)
const loggedUser = computed(() => page.props?.auth?.user ?? null)
const userDashboardStats = computed(() => ({
    balance: Number(page.props?.stats?.balance ?? loggedUser.value?.balance ?? 0),
    orders: Number(page.props?.stats?.orders ?? 0),
    shipped: Number(page.props?.stats?.shippedOrders ?? 0),
    wishlist: Number(page.props?.stats?.wishlistCount ?? 0),
}))
const scrolled = ref(false)
const heroSceneEl = ref(null)
const heroRotate = ref(0)
const productRotate = ref({})

const formatCurrency = (value) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value || 0))

const img = {
    hero: 'https://i.pinimg.com/1200x/e2/e0/e3/e2e0e34bba2597175fa84c190928c204.jpg',
    plastic: 'https://images.unsplash.com/photo-1572964734607-0051976fac79?q=80&w=900&auto=format&fit=crop',
    paper: 'https://images.unsplash.com/photo-1720788613534-fbfce4cf7495?q=80&w=900&auto=format&fit=crop',
    metal: 'https://images.unsplash.com/photo-1671362935207-d9abfc5b9509?q=80&w=900&auto=format&fit=crop',
    reusable: 'https://i.pinimg.com/736x/3a/15/b9/3a15b9614d5bc65e9087fe7b6ae40698.jpg',
    environment: 'https://images.unsplash.com/photo-1746565212969-2e1c7fbe9a82?q=80&w=1200&auto=format&fit=crop',
}

const categories = [
    { image: img.plastic, title: 'Plastik', desc: 'Botol, wadah dan material plastik yang masih dapat dimanfaatkan.', count: '1.2K+ item', icon: 'fa-recycle' },
    { image: img.paper, title: 'Kertas', desc: 'Kardus, kertas dan material yang dapat kembali digunakan.', count: '860+ item', icon: 'fa-file-alt' },
    { image: img.metal, title: 'Logam', desc: 'Material logam dan barang yang masih memiliki nilai.', count: '540+ item', icon: 'fa-cube' },
    { image: img.reusable, title: 'Barang Bekas', desc: 'Barang yang masih layak pakai dan siap menemukan pemilik baru.', count: '2.4K+ item', icon: 'fa-bag-shopping' },
]

const products = [
    { image: img.plastic, category: 'Material daur ulang', title: 'Botol Plastik Terpilah', rating: 4.8, sold: '128' },
    { image: img.reusable, category: 'Barang bekas', title: 'Paket Barang Reusable', rating: 4.9, sold: '96' },
    { image: img.metal, category: 'Material', title: 'Material Logam Pilihan', rating: 4.7, sold: '74' },
]

const handleScroll = () => {
    scrolled.value = window.scrollY > 30
}

function handleHeroInspect(event) {
    if (!heroSceneEl.value) return
    const rect = heroSceneEl.value.getBoundingClientRect()
    const center = rect.left + rect.width / 2
    const offset = ((event.clientX - center) / rect.width) * 28
    heroRotate.value = Math.max(-18, Math.min(18, offset))
}

function resetHeroInspect() {
    heroRotate.value = 0
}

function handleProductInspect(event, key) {
    const panel = event.currentTarget
    const rect = panel.getBoundingClientRect()
    const center = rect.left + rect.width / 2
    const offset = ((event.clientX - center) / rect.width) * 18
    productRotate.value[key] = Math.max(-12, Math.min(12, offset))
}

function resetProductInspect(key) {
    productRotate.value[key] = 0
}

function handleTilt(e) {
    const el = e.currentTarget
    const rect = el.getBoundingClientRect()
    const x = (e.clientX - rect.left) / rect.width - 0.5
    const y = (e.clientY - rect.top) / rect.height - 0.5
    el.style.setProperty('--ry', `${x * 12}deg`)
    el.style.setProperty('--rx', `${-y * 12}deg`)
}

function resetTilt(e) {
    const el = e.currentTarget
    el.style.setProperty('--rx', '0deg')
    el.style.setProperty('--ry', '0deg')
    el.style.setProperty('--tz', '0px')
}


function liftTilt(e) {
    e.currentTarget.style.setProperty('--tz', '18px')
}

onMounted(() => {
    AOS.refresh()
    window.addEventListener('scroll', handleScroll)
    if (heroSceneEl.value) {
        heroSceneEl.value.addEventListener('pointermove', handleHeroInspect)
        heroSceneEl.value.addEventListener('pointerleave', resetHeroInspect)
    }
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
    if (heroSceneEl.value) {
        heroSceneEl.value.removeEventListener('pointermove', handleHeroInspect)
        heroSceneEl.value.removeEventListener('pointerleave', resetHeroInspect)
    }
})
</script>

<template>
    <Head title="Lumira - Layanan Ulang Material Ramah" />

    <div class="lumira min-h-screen overflow-x-hidden bg-[#f0f5f0] text-slate-900">

        <!-- ================================================= -->
        <!-- NAVBAR -->
        <!-- ================================================= -->

        <header
            class="sticky top-0 z-50 transition-all duration-500"
            :class="scrolled ? 'bg-white/95 shadow-sm backdrop-blur-xl' : 'bg-white/70 backdrop-blur-md'"
        >
            <div class="mx-auto flex max-w-7xl items-center gap-3 px-3 py-3 sm:gap-4 sm:px-6 lg:px-8">

                <Link href="/" class="group flex shrink-0 items-center gap-3">
                    <img src="/images/lumira.png" alt="LUMIRA" class="h-11 w-11" />
                    <div class="hidden sm:block">
                        <h1 class="text-xl font-black leading-none tracking-tight text-[#0b2617]">LUMIRA</h1>
                        <p class="mt-1 text-[9px] font-bold uppercase tracking-[0.22em] text-slate-400">
                            Layanan Ulang Material 
                        </p>
                    </div>
                </Link>

                <div class="hidden flex-1 lg:block"></div>

                <div class="min-w-0 flex-1 overflow-x-auto overscroll-contain py-1 lg:flex-none lg:overflow-visible">
                    <div class="flex w-max min-w-full shrink-0 items-center justify-end gap-2 lg:w-auto">
                    <template v-if="canLogin">
                        <template v-if="isLoggedIn">
                            <div class="hidden items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-semibold text-slate-700 shadow-sm sm:flex">
                                <i class="fas fa-wallet text-[11px] text-slate-500"></i>
                                <span>{{ formatCurrency(userDashboardStats.balance) }}</span>
                            </div>
                            <Link :href="route('topup.index')" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-lg text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900" title="Top Up">
                                <i class="fas fa-wallet"></i>
                            </Link>
                            <Link :href="route('cart.index')" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-lg text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900" title="Keranjang">
                                <i class="fas fa-shopping-cart"></i>
                            </Link>
                            <Link :href="route('wishlist.index')" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-lg text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-pink-600" title="Wishlist">
                                <i class="fas fa-heart"></i>
                            </Link>
                            <Link :href="route('orders.index')" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-lg text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900" title="Pesanan Saya">
                                <i class="fas fa-box"></i>
                            </Link>
                            <Link v-if="loggedUser?.seller_status !== 'suspended' && loggedUser?.role === 'seller'" :href="route('seller.withdrawals.index')" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-lg text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-emerald-600" title="Tarik Dana">
                                <i class="fas fa-money-bill-wave"></i>
                            </Link>
                            <Link href="/chat" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-lg text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900" title="Pesan Masuk">
                                <i class="fas fa-comments"></i>
                            </Link>
                            <Link :href="route('notifications.index')" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-lg text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900" title="Notifikasi">
                                <i class="fas fa-bell"></i>
                            </Link>
                            <Link :href="route('profile.edit')" class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-2 py-1.5 pr-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:text-slate-900" title="Profil pengguna">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-base text-slate-700">
                                    <i class="fas fa-user"></i>
                                </span>
                                <span class="max-w-[90px] truncate">{{ page.props.auth.user.name }}</span>
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-lg text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900"
                                title="Keluar"
                            >
                                <i class="fas fa-power-off"></i>
                            </Link>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" class="hidden rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-100 hover:text-[#0c7c43] sm:block">
                                Masuk
                            </Link>
                            <Link v-if="canRegister" :href="route('register')" class="btn-shine rounded-xl bg-[#0c7c43] px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-green-900/20 transition hover:-translate-y-0.5 hover:bg-[#0b2617]">
                                Daftar
                            </Link>
                        </template>
                    </template>
                    </div>
                </div>

            </div>
        </header>


        <section class="mx-auto max-w-7xl px-6 pt-6 lg:px-8" data-aos="fade-up">
            <div v-if="featuredBanner" class="group relative overflow-hidden rounded-2xl shadow-2xl shadow-green-900/20 transition-all duration-500 hover:shadow-green-900/30">
                <!-- Background with image and overlay -->
                <div class="absolute inset-0 -z-10">
                    <img
                        v-if="featuredBanner.image"
                        :src="`/storage/${featuredBanner.image}`"
                        :alt="featuredBanner.title"
                        class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110"
                    />
                    <!-- Dark overlay -->
                    <div class="absolute inset-0 bg-black/40"></div>
                    <!-- Gradient overlay left to right -->
                    <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/30 to-transparent"></div>
                </div>

                <div class="relative flex flex-col justify-center gap-4 px-6 py-16 sm:py-20 lg:px-8">
                    <!-- Label -->
                    <div class="flex items-center gap-2">
                        <div class="h-1 w-6 rounded-full bg-green-300"></div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-green-200">Promo Spesial</p>
                    </div>

                    <!-- Main title with highlighted text -->
                    <div class="max-w-2xl">
                        <h3 class="text-4xl font-black leading-tight text-white sm:text-5xl lg:text-6xl">
                            {{ featuredBanner.title }}
                            <span v-if="featuredBanner.title.includes('%')" class="text-green-300">{{ featuredBanner.title.split(' ').pop() }}</span>
                        </h3>
                    </div>

                    <!-- Subtitle -->
                    <p class="max-w-md text-base font-semibold text-gray-200 sm:text-lg">
                        {{ featuredBanner.description || 'Jangan lewatkan penawaran istimewa dari Lumira.' }}
                    </p>

                    <!-- CTA Button -->
                    <div class="mt-4 flex items-center gap-4">
                        <a
                            v-if="featuredBanner.button_link"
                            :href="featuredBanner.button_link"
                            class="inline-flex items-center gap-2 rounded-lg bg-[#2cb342] px-6 py-2.5 font-bold text-white transition duration-300 hover:bg-[#25a03d] hover:-translate-y-0.5"
                        >
                            {{ featuredBanner.button_text || 'Lihat Promo' }}
                            <span class="transition group-hover:translate-x-1">→</span>
                        </a>
                        <div class="h-10 w-px bg-white/20"></div>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-300">Terbatas</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================================================= -->
        <!-- HERO -->
        <!-- ================================================= -->

        <section class="relative overflow-hidden pt-14">

            <div class="absolute -right-40 -top-40 h-[550px] w-[550px] rounded-full bg-green-200/30 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 h-[450px] w-[450px] rounded-full bg-emerald-200/20 blur-3xl"></div>
            <div class="pointer-events-none absolute left-1/2 top-24 h-72 w-72 -translate-x-1/2 rounded-full bg-green-200/15 blur-3xl"></div>

            <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-6 pb-24 pt-10 lg:grid-cols-[1fr_0.9fr] lg:px-8">

                <!-- HERO LEFT -->
                <div data-aos="fade-right" data-aos-duration="1000">

                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-green-100 bg-white px-4 py-2 text-sm font-bold text-slate-600 shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#0c7c43] opacity-60"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-[#0c7c43]"></span>
                        </span>
                        <span>526 transaksi selesai minggu ini</span>
                    </div>

                    <h2 class="max-w-4xl text-5xl font-black leading-[1.03] tracking-[-0.045em] text-slate-950 sm:text-6xl lg:text-[68px]">
                        Berikan
                        <span class="bg-gradient-to-r from-[#0c7c43] to-[#0b2617] bg-clip-text text-transparent">nilai baru</span>
                        <br />
                        pada yang
                        <span class="relative inline-block">
                            tersisa.
                            <svg class="absolute -bottom-2 left-0 w-full text-green-300" viewBox="0 0 200 12" preserveAspectRatio="none" fill="none">
                                <path d="M2 9C40 2 160 2 198 9" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </h2>

                    <p class="mt-7 max-w-xl text-lg leading-8 text-slate-500 sm:text-xl">
                        <strong class="text-slate-800">LUMIRA</strong> adalah
                        <strong class="text-[#0c7c43]">Layanan Ulang Material Ramah</strong>
                        — marketplace untuk menjual, membeli, dan memberi kehidupan baru
                        pada barang yang masih bernilai.
                    </p>

                    <div class="mt-9 flex flex-wrap gap-4">
                        <template v-if="!isLoggedIn">
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="btn-shine group inline-flex items-center gap-3 rounded-2xl bg-[#0c7c43] px-7 py-4 font-black text-white shadow-xl shadow-green-900/25 transition duration-300 hover:-translate-y-1 hover:bg-[#0b2617] hover:shadow-2xl"
                            >
                                Mulai Berjualan
                                <span class="transition duration-300 group-hover:translate-x-1">→</span>
                            </Link>
                            <a
                                href="#produk"
                                class="group inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-7 py-4 font-bold text-slate-700 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-green-200 hover:text-[#0c7c43]"
                            >
                                Jelajahi Produk
                            </a>
                        </template>
                        <template v-else>
                            <Link :href="route('marketplace')" class="btn-shine group inline-flex items-center gap-3 rounded-2xl bg-[#0c7c43] px-7 py-4 font-black text-white shadow-xl shadow-green-900/20 transition hover:-translate-y-1">
                                Marketplace
                                <span class="transition group-hover:translate-x-1">→</span>
                            </Link>
                            <Link v-if="page.props.auth.user.seller_status !== 'suspended'" :href="route('seller.apply')" class="group inline-flex items-center gap-3 rounded-2xl border border-green-200 bg-[#edf9ee] px-7 py-4 font-bold text-[#0c7c43] shadow-sm transition hover:-translate-y-1">
                                Daftar Seller
                            </Link>
                        </template>
                    </div>

                    <!-- TRUST STRIP -->
                    <div class="mt-12 grid max-w-xl grid-cols-3 gap-3 border-t border-slate-200 pt-8" data-aos="fade-up" data-aos-delay="300">
                        <div>
                            <p class="text-2xl font-black text-[#0b2617]">12K+</p>
                            <p class="mt-1 text-xs font-medium text-slate-400 sm:text-sm">Pengguna aktif</p>
                        </div>
                        <div class="border-l border-slate-200 pl-4">
                            <p class="text-2xl font-black text-[#0b2617]">4.9<span class="text-base">/5</span></p>
                            <p class="mt-1 text-xs font-medium text-slate-400 sm:text-sm">Rating pengguna</p>
                        </div>
                        <div class="border-l border-slate-200 pl-4">
                            <p class="text-2xl font-black text-[#0b2617]">∞</p>
                            <p class="mt-1 text-xs font-medium text-slate-400 sm:text-sm">Nilai berulang</p>
                        </div>
                    </div>

                </div>

                <!-- HERO RIGHT -->
                <div class="relative mx-auto h-[540px] w-full max-w-xl" data-aos="fade-left" data-aos-duration="1100">

                    <div class="hero-image group absolute right-0 top-5 h-[440px] w-[88%] overflow-hidden rounded-[3rem] bg-gradient-to-br from-[#e8f5e9] via-[#f0f5f0] to-[#dceee0] shadow-2xl shadow-green-950/25">
                        <div
                            ref="heroSceneEl"
                            class="absolute inset-0 h-full w-full cursor-grab touch-none active:cursor-grabbing"
                            data-aos="zoom-in"
                            data-aos-duration="1200"
                            aria-label="Inspect product photo"
                        >
                            <img
                                :src="img.hero"
                                alt="Produk material daur ulang"
                                class="media-reveal h-full w-full object-cover transition duration-200 ease-out"
                                :style="{ transform: `perspective(1200px) rotateY(${heroRotate}deg) scale(1.06)` }"
                            />
                        </div>
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#0b2617]/70 via-transparent to-transparent"></div>
                        <div class="pointer-events-none absolute bottom-7 left-7 right-7 flex items-end justify-between text-white">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.25em] text-white/70">LUMIRA</p>
                                <p class="mt-2 text-3xl font-black leading-tight">Jual. <br />Gunakan kembali.</p>
                            </div>
                        </div>
                    </div>

                    <div class="hero-floating absolute left-0 top-20 z-10 w-56 rounded-3xl border border-white bg-white/95 p-5 shadow-2xl backdrop-blur transition duration-300 hover:-translate-y-1 hover:shadow-green-900/20">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-lg text-slate-500">
                                <i class="fas fa-recycle"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Misi Lumira</p>
                                <p class="font-black text-slate-900">Kurangi terbuang</p>
                            </div>
                        </div>
                    </div>

                    <div class="hero-floating-delayed absolute bottom-7 left-0 z-10 w-60 rounded-3xl bg-[#0b2617] p-5 text-white shadow-2xl transition duration-300 hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-green-200">Dampak</p>
                                <p class="mt-1 text-xl font-black">Lebih hijau.</p>
                            </div>
                            <i class="fas fa-seedling text-2xl text-white/90"></i>
                        </div>
                        <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-white/20">
                            <div class="progress-bar h-full rounded-full bg-white"></div>
                        </div>
                    </div>

                    <div class="absolute -bottom-1 right-0 h-28 w-28 rounded-full border border-green-200 bg-green-50"></div>
                </div>

            </div>
        </section>


        <!-- ================================================= -->
        <!-- TRUST BADGES -->
        <!-- ================================================= -->

        <section class="border-y border-slate-200 bg-white">
            <div class="mx-auto grid max-w-7xl grid-cols-2 gap-6 px-6 py-8 sm:grid-cols-4 lg:px-8">
                <div
                    v-for="(badge, i) in [
                        { icon: 'shield', title: 'Transaksi Aman', desc: 'Terverifikasi & terpercaya' },
                        { icon: 'truck', title: 'Kirim ke Seluruh RI', desc: 'Jangkauan nasional' },
                        { icon: 'leaf', title: 'Ramah Lingkungan', desc: 'Mengurangi limbah' },
                        { icon: 'star', title: 'Rating 4.9/5', desc: 'Dari 12K+ pengguna' },
                    ]"
                    :key="i"
                    class="group flex items-center gap-3"
                    data-aos="fade-up"
                    :data-aos-delay="i * 100"
                >
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 transition duration-300 group-hover:bg-[#0c7c43] group-hover:text-white">
                        <svg v-if="badge.icon === 'shield'" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="m9 12 2 2 4-4"/></svg>
                        <svg v-else-if="badge.icon === 'truck'" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="7" width="13" height="9"/><path d="M14 10h4l3 3v3h-7z"/><circle cx="5.5" cy="18" r="1.5"/><circle cx="17.5" cy="18" r="1.5"/></svg>
                        <svg v-else-if="badge.icon === 'leaf'" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 4 13c0-5 4-9 12-10 0 8-2 13-5 17z"/><path d="M9 15c3-3 5-6 6-10"/></svg>
                        <svg v-else viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m12 2 3.1 6.3 6.9 1-5 4.9L18.2 21 12 17.8 5.8 21l1.2-6.8-5-4.9 6.9-1z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-900">{{ badge.title }}</p>
                        <p class="text-xs text-slate-400">{{ badge.desc }}</p>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================================================= -->
        <!-- ABOUT -->
        <!-- ================================================= -->

        <section id="tentang" class="bg-white py-20">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <div data-aos="fade-right">
                        <p class="text-sm font-black uppercase tracking-[0.2em] text-[#0c7c43]">Tentang Lumira</p>
                        <h2 class="mt-4 text-4xl font-black tracking-tight text-slate-950 sm:text-5xl">
                            Sesuatu yang tersisa
                            <span class="text-[#0c7c43]">masih bisa berarti.</span>
                        </h2>
                    </div>
                    <div data-aos="fade-left">
                        <p class="text-lg leading-8 text-slate-500">
                            Lumira hadir sebagai marketplace yang membantu memperpanjang usia
                            barang dan material. Barang yang tidak lagi digunakan dapat berpindah
                            tangan, menghasilkan nilai, dan mengurangi barang yang berakhir
                            menjadi sampah.
                        </p>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================================================= -->
        <!-- CATEGORY -->
        <!-- ================================================= -->

        <section class="bg-gradient-to-b from-[#edf7ee] via-[#f3faf4] to-[#edf6ef] py-24">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">

                <div class="mb-12 flex flex-wrap items-end justify-between gap-4" data-aos="fade-up">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.28em] text-[#0c7c43]">Jelajahi</p>
                        <h2 class="mt-3 text-4xl font-black leading-[0.92] tracking-[-0.08em] text-slate-950 sm:text-5xl xl:text-[4.5rem]">
                            Material yang punya <span class="text-[#0c7c43]">cerita.</span>
                        </h2>
                    </div>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                    <div
                        v-for="(cat, i) in categories"
                        :key="cat.title"
                        data-aos="zoom-in-up"
                        :data-aos-delay="i * 120"
                        :style="{ '--card-delay': `${i * 120}ms` }"
                        class="category-card reveal-card group relative overflow-hidden rounded-[2.1rem] border border-[#dfeee4] bg-white shadow-[0_22px_55px_rgba(15,23,42,0.06)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_28px_80px_rgba(12,124,67,0.15)]"
                        @mousemove="handleTilt"
                        @mouseenter="liftTilt"
                        @mouseleave="resetTilt"
                    >
                        <div class="relative h-60 overflow-hidden rounded-[1.65rem] m-3 mb-0">
                            <img :src="cat.image" :alt="cat.title" class="media-reveal h-full w-full object-cover transition duration-700 group-hover:scale-110" />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0b2617]/15 via-transparent to-transparent"></div>
                            <span class="absolute right-3 top-3 rounded-full bg-white/95 px-3 py-1 text-[11px] font-black text-[#0c7c43] shadow-sm shadow-slate-900/5 backdrop-blur-sm">
                                {{ cat.count }}
                            </span>
                        </div>

                        <div class="px-5 pb-6 pt-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#edf9ee] text-[#0c7c43] shadow-inner shadow-green-900/5 transition duration-300 group-hover:-rotate-6 group-hover:bg-[#0c7c43] group-hover:text-white">
                                <i :class="['fas', cat.icon, 'text-lg']"></i>
                            </div>

                            <h3 class="mt-5 text-[2.15rem] font-black leading-none tracking-[-0.07em] text-slate-900">{{ cat.title }}</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-500">{{ cat.desc }}</p>

                
                        </div>
                    </div>
                </div>

            </div>
        </section>


        <!-- ================================================= -->
        <!-- CARA KERJA -->
        <!-- ================================================= -->

        <section id="cara-kerja" class="bg-white py-24">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">

                <div class="text-center" data-aos="fade-up">
                    <p class="text-sm font-black uppercase tracking-[0.2em] text-[#0c7c43]">Cara kerja</p>
                    <h2 class="mt-3 text-4xl font-black tracking-tight text-slate-950 sm:text-5xl">
                        Sederhana untuk kamu, <br /> berarti untuk bumi.
                    </h2>
                </div>

                <div class="relative mt-16 grid gap-10 md:grid-cols-3">
                    <div class="pointer-events-none absolute left-0 right-0 top-10 hidden h-px border-t border-dashed border-slate-200 md:block"></div>

                    <div
                        v-for="(step, i) in [
                            { n: '01', title: 'Temukan', desc: 'Cari barang atau material yang kamu butuhkan di marketplace Lumira.' },
                            { n: '02', title: 'Gunakan kembali', desc: 'Berikan kesempatan kedua pada barang yang masih memiliki nilai.' },
                            { n: '03', title: 'Beri dampak', desc: 'Setiap transaksi membantu mengurangi barang yang berakhir sebagai sampah.' },
                        ]"
                        :key="step.n"
                        data-aos="zoom-in"
                        :data-aos-delay="i * 150"
                        class="group relative text-center"
                    >
                        <div
                            class="relative z-10 mx-auto flex h-20 w-20 items-center justify-center rounded-full text-xl font-black shadow-sm transition duration-300 group-hover:scale-110"
                            :class="i === 1 ? 'bg-[#0b2617] text-white shadow-xl shadow-green-900/25' : 'bg-slate-100 text-slate-500'"
                        >
                            {{ step.n }}
                        </div>
                        <h3 class="mt-7 text-xl font-black">{{ step.title }}</h3>
                        <p class="mx-auto mt-3 max-w-xs text-sm leading-7 text-slate-500">{{ step.desc }}</p>
                    </div>
                </div>

            </div>
        </section>


        <!-- ================================================= -->
        <!-- PRODUCT -->
        <!-- ================================================= -->

        <section id="produk" class="bg-[#f0f5f0] py-24">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">

                <div class="flex flex-wrap items-end justify-between gap-4" data-aos="fade-up">
                    <div>
                        <p class="text-sm font-black uppercase tracking-[0.2em] text-[#0c7c43]">Pilihan Lumira</p>
                        <h2 class="mt-3 text-4xl font-black tracking-tight text-slate-950 sm:text-5xl">
                            Barang yang siap <span class="text-[#0c7c43]">digunakan lagi.</span>
                        </h2>
                    </div>
                </div>

                <div class="mt-14 grid gap-6 md:grid-cols-3">
                    <div
                        v-for="(p, i) in products"
                        :key="p.title"
                        data-aos="fade-up"
                        :data-aos-delay="i * 150"
                        class="tilt-card group overflow-hidden rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-100 transition-shadow duration-500 hover:shadow-2xl hover:shadow-green-900/10"
                        @mousemove="handleTilt"
                        @mouseenter="liftTilt"
                        @mouseleave="resetTilt"
                    >
                        <div class="relative h-64 overflow-hidden" data-aos="zoom-in" :data-aos-delay="i * 120">
                            <img
                                :src="p.image"
                                :alt="p.title"
                                class="media-reveal h-full w-full cursor-grab object-cover transition duration-700 group-hover:scale-110"
                                :style="{ transform: `perspective(1200px) rotateY(${productRotate[p.title] || 0}deg) scale(1.06)` }"
                                @pointermove="handleProductInspect($event, p.title)"
                                @pointerleave="resetProductInspect(p.title)"
                            />

                            <span class="absolute left-5 top-5 rounded-full border border-white/80 bg-white/90 px-3 py-1.5 text-[11px] font-black uppercase tracking-[0.16em] text-[#0c7c43] shadow-lg shadow-black/10 backdrop-blur-sm">
                                INSPECT
                            </span>
                            <span class="absolute bottom-4 right-4 flex h-10 w-10 items-center justify-center rounded-full border border-white/70 bg-black/20 text-[12px] font-bold text-white backdrop-blur-sm">
                                360°
                            </span>
                        </div>

                        <div class="p-6">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">{{ p.category }}</p>
                            <h3 class="mt-2 text-xl font-black">{{ p.title }}</h3>

                            <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
                                <span class="flex items-center gap-1 font-bold text-amber-500">
                                    <i class="fas fa-star"></i>
                                    {{ p.rating }}
                                </span>
                                <span class="text-slate-300">•</span>
                                <span>{{ p.sold }} terjual</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>


        <!-- ================================================= -->
        <!-- ENVIRONMENT -->
        <!-- ================================================= -->

        <section class="bg-[#0b2617] py-24">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid items-center gap-14 lg:grid-cols-2">

                    <div data-aos="fade-right">
                        <div class="overflow-hidden rounded-[2.5rem] shadow-2xl">
                            <img
                                :src="img.environment"
                                alt="Lingkungan bersih"
                                class="media-reveal h-[450px] w-full object-cover transition duration-1000 hover:scale-105"
                            />
                        </div>
                    </div>

                    <div data-aos="fade-left">
                        <p class="text-sm font-black uppercase tracking-[0.2em] text-green-300">Lebih dari marketplace</p>
                        <h2 class="mt-4 text-4xl font-black leading-tight text-white sm:text-5xl">
                            Karena setiap barang
                            <span class="text-green-300">punya kesempatan kedua.</span>
                        </h2>
                        <p class="mt-6 text-lg leading-8 text-green-100/60">
                            Lumira bukan hanya tempat jual beli. Kami ingin menciptakan
                            kebiasaan baru: menggunakan kembali sebelum membuang.
                        </p>

                        <div class="mt-9 space-y-5">
                            <div
                                v-for="(item, i) in [
                                    { icon: 'fa-recycle', title: 'Kurangi barang terbuang', desc: 'Barang yang masih bernilai dapat menemukan pemilik berikutnya.' },
                                    { icon: 'fa-plus', title: 'Tambahkan nilai', desc: 'Seller dapat mengubah barang yang tidak terpakai menjadi penghasilan.' },
                                    { icon: 'fa-seedling', title: 'Jaga lingkungan', desc: 'Satu keputusan kecil dapat berkontribusi pada lingkungan yang lebih baik.' },
                                ]"
                                :key="item.title"
                                class="group flex gap-4 rounded-2xl p-2 transition duration-300 hover:bg-white/5"
                                data-aos="fade-up"
                                :data-aos-delay="i * 120"
                            >
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10 text-lg text-slate-300 transition duration-300 group-hover:bg-[#0c7c43] group-hover:text-white">
                                    <i :class="['fas', item.icon]" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-white">{{ item.title }}</h3>
                                    <p class="mt-1 text-sm leading-6 text-green-100/50">{{ item.desc }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <!-- ================================================= -->
        <!-- CTA -->
        <!-- ================================================= -->

        <section class="relative overflow-hidden bg-[#0c7c43] py-20" data-aos="fade-up">
            <div class="absolute -left-24 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -right-24 -bottom-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>

            <div class="relative mx-auto max-w-4xl px-6 text-center">
                <p class="text-sm font-black uppercase tracking-[0.25em] text-green-100">LUMIRA</p>
                <h2 class="mt-4 text-4xl font-black tracking-tight text-white sm:text-6xl">
                    Ubah sampah jadi rupiah, sulap plastik jadi produk ciamik demi bumi yang lebih apik!
                </h2>
                <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-green-50">
                    Berikan nilai baru pada yang tersisa dan jadilah bagian dari
                    perjalanan menuju lingkungan yang lebih bersih.
                </p>

                <div class="mt-9">
                    <Link
                        v-if="canRegister && !isLoggedIn"
                        :href="route('register')"
                        class="btn-shine inline-flex items-center gap-3 rounded-2xl bg-white px-8 py-4 font-black text-[#0c7c43] shadow-2xl transition hover:-translate-y-1"
                    >
                        Bergabung dengan Lumira <span>→</span>
                    </Link>
                    <Link
                        v-else-if="isLoggedIn"
                        :href="route('marketplace')"
                        class="btn-shine inline-flex items-center gap-3 rounded-2xl bg-white px-8 py-4 font-black text-[#0c7c43] shadow-2xl transition hover:-translate-y-1"
                    >
                        Jelajahi Marketplace <span>→</span>
                    </Link>
                </div>
            </div>
        </section>


        <!-- ================================================= -->
        <!-- FOOTER -->
        <!-- ================================================= -->

        <AppFooter />

    </div>
</template>


<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Inter:wght@400;500;600&display=swap');

.lumira {
    font-family: 'Inter', system-ui, sans-serif;
}

.lumira :deep(h1),
.lumira :deep(h2),
.lumira :deep(h3) {
    font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
}

/* Decorative emoji rendered in a muted grayscale tone to match the icon system */
.icon-mono {
    filter: grayscale(1) opacity(0.7);
}

/* Nav link underline sweep */
.nav-link::after {
    content: '';
    position: absolute;
    left: 14px;
    right: 14px;
    bottom: 6px;
    height: 2px;
    border-radius: 999px;
    background: #0c7c43;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
}
.nav-link:hover::after {
    transform: scaleX(1);
}

/* Button shine sweep on hover */
.btn-shine {
    position: relative;
    overflow: hidden;
}
.btn-shine::before {
    content: '';
    position: absolute;
    top: 0;
    left: -75%;
    width: 50%;
    height: 100%;
    background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.35), transparent);
    transform: skewX(-20deg);
    transition: left 0.6s ease;
}
.btn-shine:hover::before {
    left: 125%;
}

.hero-image {
    animation: heroFloat 6s ease-in-out infinite;
}

.media-reveal {
    animation: photoReveal 1.2s cubic-bezier(0.22, 1, 0.36, 1) both;
    transform-origin: center;
}

/* 3D tilt for category & product cards, driven by --rx/--ry/--tz set in JS */
.tilt-card {
    --rx: 0deg;
    --ry: 0deg;
    --tz: 0px;
    transform-style: preserve-3d;
    transform: perspective(900px) rotateX(var(--rx)) rotateY(var(--ry)) translateZ(var(--tz)) translateY(calc(var(--tz) * -0.4));
    transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.35s ease;
    will-change: transform;
}

.category-card {
    backdrop-filter: blur(4px);
    transform: translateY(0);
}

.category-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.36), rgba(255,255,255,0));
    pointer-events: none;
}

.category-card:hover {
    transform: translateY(-4px);
}

.reveal-card {
    animation: cardReveal 0.8s cubic-bezier(0.22, 1, 0.36, 1) both;
    animation-delay: var(--card-delay, 0ms);
}

.hero-floating {
    animation: floatingCard 4s ease-in-out infinite;
}

.hero-floating-delayed {
    animation: floatingCard 5s ease-in-out 1s infinite;
}

.progress-bar {
    width: 0;
    animation: progress 2s ease-out 0.5s forwards;
}

@keyframes heroFloat {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-12px) rotate(0.5deg); }
}

@keyframes photoReveal {
    0% {
        opacity: 0;
        transform: scale(1.08) translateY(18px);
        filter: blur(6px);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
        filter: blur(0);
    }
}

@keyframes floatingCard {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-12px); }
}

@keyframes cardReveal {
    0% {
        opacity: 0;
        transform: translateY(28px) scale(0.98);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes progress {
    from { width: 0; }
    to { width: 80%; }
}

@media (prefers-reduced-motion: reduce) {
    .hero-image,
    .hero-floating,
    .hero-floating-delayed,
    .progress-bar {
        animation: none !important;
    }
}
</style>