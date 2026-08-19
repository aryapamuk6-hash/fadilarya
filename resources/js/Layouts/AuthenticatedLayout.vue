<script setup>
import { ref, watch } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import AppFooter from '@/Components/AppFooter.vue';
import BackButton from '@/Components/BackButton.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();
const dismissedFlash = ref(false);

watch(
    () => [page.props.flash?.success, page.props.flash?.error],
    () => {
        dismissedFlash.value = false;
    },
)
</script>

<template>
    <div class="min-h-screen bg-[#f5faf6] text-slate-800 flex flex-col">
        <nav class="sticky top-0 z-40 border-b border-green-100 bg-white/90 backdrop-blur-xl shadow-sm shadow-green-900/5">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-20 justify-between">
                    <div class="flex items-center">
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('dashboard')" class="group flex items-center gap-3 transition hover:scale-105">
                                <img src="/images/lumira.png" alt="LUMIRA" class="h-12 w-12" />
                                <div class="hidden sm:block">
                                    <p class="text-lg font-black tracking-wide text-[#0b2617]">LUMIRA</p>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500">Layanan Ulang Material</p>
                                </div>
                            </Link>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        </div>
                    </div>

                    <div class="hidden sm:ms-6 sm:flex sm:items-center sm:gap-3">
                        <Link v-if="$page.props.auth.user.role !== 'admin'" :href="route('chat.index')" class="relative inline-flex h-11 w-11 items-center justify-center rounded-full border border-green-100 bg-[#f4faf5] text-lg text-slate-600 transition hover:border-[#0c7c43]/40 hover:bg-[#edf9ee] hover:text-[#0c7c43]" title="Pesan Masuk">
                            <i class="fas fa-comments"></i>
                            <span class="absolute -right-1 -top-1 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-[#0c7c43] ring-2 ring-white"></span>
                        </Link>

                        <Link :href="route('notifications.index')" class="relative inline-flex h-11 w-11 items-center justify-center rounded-full border border-green-100 bg-[#f4faf5] text-lg text-slate-600 transition hover:border-[#0c7c43]/40 hover:bg-[#edf9ee] hover:text-[#0c7c43]" title="Notifikasi">
                            <i class="fas fa-bell"></i>
                            <span v-if="$page.props.notificationsCount > 0" class="absolute -right-1 -top-1 flex min-h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white ring-2 ring-white">
                                {{ $page.props.notificationsCount > 9 ? '9+' : $page.props.notificationsCount }}
                            </span>
                        </Link>

                        <div class="relative ms-1">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-full">
                                        <button type="button" class="inline-flex items-center rounded-full border border-green-100 bg-[#f4faf5] px-4 py-2 text-sm font-semibold leading-4 text-slate-700 transition hover:border-green-200 hover:bg-white hover:text-[#0c7c43] focus:outline-none">
                                            {{ $page.props.auth.user.name }}
                                            <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')" class="flex items-center gap-3 text-slate-700 hover:text-[#0c7c43]">
                                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#edf9ee] text-[#0c7c43]">
                                            <i class="fas fa-user-circle text-sm"></i>
                                        </span>
                                        <span>Profile</span>
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button" class="flex items-center gap-3 text-slate-700 hover:text-[#0c7c43]">
                                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-red-50 text-red-500">
                                            <i class="fas fa-power-off text-sm"></i>
                                        </span>
                                        <span>Log Out</span>
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center rounded-xl border border-green-100 bg-white p-2 text-slate-500 transition hover:bg-green-50 hover:text-[#0c7c43] focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                <div class="space-y-1 border-t border-green-100 bg-white px-3 pb-3 pt-2">
                    <ResponsiveNavLink v-if="$page.props.auth.user.role === 'user'" :href="route('marketplace')" class="flex min-h-11 items-center gap-3 rounded-xl text-slate-700">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-[#0c7c43]"><i class="fas fa-store text-sm"></i></span>
                        <span>Marketplace</span>
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="$page.props.auth.user.role === 'user'" :href="route('cart.index')" class="flex min-h-11 items-center gap-3 rounded-xl text-slate-700">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600"><i class="fas fa-shopping-cart text-sm"></i></span>
                        <span>Keranjang</span>
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="$page.props.auth.user.role === 'user'" :href="route('orders.index')" class="flex min-h-11 items-center gap-3 rounded-xl text-slate-700">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600"><i class="fas fa-box text-sm"></i></span>
                        <span>Pesanan Saya</span>
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="$page.props.auth.user.role === 'seller'" :href="route('seller.products.index')" class="flex min-h-11 items-center gap-3 rounded-xl text-slate-700">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-[#0c7c43]"><i class="fas fa-boxes text-sm"></i></span>
                        <span>Produk Saya</span>
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="$page.props.auth.user.role === 'seller'" :href="route('seller.orders.index')" class="flex min-h-11 items-center gap-3 rounded-xl text-slate-700">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600"><i class="fas fa-truck text-sm"></i></span>
                        <span>Pesanan Masuk</span>
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="$page.props.auth.user.role === 'admin'" :href="route('admin.dashboard')" class="flex min-h-11 items-center gap-3 rounded-xl text-slate-700">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-[#0c7c43]"><i class="fas fa-chart-line text-sm"></i></span>
                        <span>Dashboard Admin</span>
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="$page.props.auth.user.role === 'admin'" :href="route('admin.products.index')" class="flex min-h-11 items-center gap-3 rounded-xl text-slate-700">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600"><i class="fas fa-boxes text-sm"></i></span>
                        <span>Kelola Produk</span>
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('notifications.index')" class="flex items-center gap-3 text-slate-700">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#edf9ee] text-[#0c7c43]">
                            <i class="fas fa-bell text-sm"></i>
                        </span>
                        <span>Notifikasi</span>
                        <span v-if="$page.props.notificationsCount > 0" class="ml-auto inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">
                            {{ $page.props.notificationsCount > 9 ? '9+' : $page.props.notificationsCount }}
                        </span>
                    </ResponsiveNavLink>
                </div>

                <div class="border-t border-green-100 pb-1 pt-4">
                    <div class="min-w-0 px-4">
                        <div class="truncate text-base font-medium text-slate-800">{{ $page.props.auth.user.name }}</div>
                        <div class="truncate text-sm font-medium text-slate-500">{{ $page.props.auth.user.email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')" class="flex items-center gap-3 text-slate-700">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#edf9ee] text-[#0c7c43]">
                                <i class="fas fa-user-circle text-sm"></i>
                            </span>
                            <span>Profile</span>
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="flex items-center gap-3 text-slate-700">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-red-50 text-red-500">
                                <i class="fas fa-power-off text-sm"></i>
                            </span>
                            <span>Log Out</span>
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <header v-if="$slots.header" class="border-b border-green-100 bg-gradient-to-r from-[#f2faf4] via-white to-[#edf9ee]">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <div v-if="!dismissedFlash && (page.props.flash?.success || page.props.flash?.error)" class="pointer-events-none fixed inset-x-4 bottom-4 z-[100] sm:left-auto sm:right-5 sm:max-w-md">
            <div v-if="page.props.flash?.success" class="pointer-events-auto flex items-start gap-3 rounded-2xl border border-emerald-200 bg-white/95 p-4 text-emerald-800 shadow-[0_18px_50px_rgba(12,124,67,0.2)] ring-1 ring-emerald-100 backdrop-blur-xl" role="status">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#edf9ee] text-[#0c7c43]"><i class="fas fa-check-circle"></i></span>
                <div class="min-w-0 flex-1"><p class="text-xs font-black uppercase tracking-[0.16em] text-[#0c7c43]">Lumira</p><p class="mt-1 break-words text-sm font-semibold">{{ page.props.flash.success }}</p></div>
                <button type="button" @click="dismissedFlash = true" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup pemberitahuan"><i class="fas fa-times"></i></button>
            </div>
            <div v-else class="pointer-events-auto flex items-start gap-3 rounded-2xl border border-red-200 bg-white/95 p-4 text-red-800 shadow-[0_18px_50px_rgba(185,28,28,0.16)] ring-1 ring-red-100 backdrop-blur-xl" role="alert">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600"><i class="fas fa-exclamation-circle"></i></span>
                <div class="min-w-0 flex-1"><p class="text-xs font-black uppercase tracking-[0.16em] text-red-600">Perhatian</p><p class="mt-1 break-words text-sm font-semibold">{{ page.props.flash.error }}</p></div>
                <button type="button" @click="dismissedFlash = true" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup pemberitahuan"><i class="fas fa-times"></i></button>
            </div>
        </div>

        <main class="min-w-0 flex-1 mx-auto w-full max-w-7xl overflow-x-hidden px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-5 max-w-full">
                <BackButton fallback="/dashboard" />
            </div>
            <slot />
        </main>

        <AppFooter />
    </div>
</template>
