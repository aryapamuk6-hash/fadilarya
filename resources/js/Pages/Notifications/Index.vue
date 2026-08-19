<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    notifications: Array
})

const sortedNotifications = computed(() => {
    return [...(props.notifications ?? [])].sort((a, b) => {
        const dateDifference = new Date(b.created_at) - new Date(a.created_at)

        return dateDifference || b.id - a.id
    })
})

function read(id)
{
    router.post(
        route(
            'notifications.read',
            id
        )
    )
}

function readAll()
{
    router.post(
        route(
            'notifications.readAll'
        )
    )
}
</script>

<template>
    <Head title="Notifikasi" />

    <AuthenticatedLayout>

        <template #header>
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[#0c7c43]">Pusat Pemberitahuan</p>
                    <h2 class="mt-2 text-2xl font-black text-[#0b2617] md:text-3xl">
                        Notifikasi
                    </h2>
                </div>
                <button
                    v-if="notifications.some(n => !n.is_read)"
                    @click="readAll"
                    class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-bold text-[#0c7c43] shadow-sm ring-1 ring-green-100 transition hover:bg-[#edf9ee] sm:w-auto sm:rounded-full"
                >
                    <i class="fas fa-check-double"></i>
                    Tandai Semua Dibaca
                </button>
            </div>
        </template>

        <div class="min-h-screen overflow-x-hidden bg-[#f6f8f6]">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

                <!-- Empty State -->
                <div
                    v-if="notifications.length === 0"
                    class="rounded-[2rem] border-2 border-dashed border-green-200 bg-gradient-to-br from-[#edf9ee] to-[#f6f8f6] p-12 text-center"
                    data-aos="fade-up"
                >
                    <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-white shadow-lg ring-1 ring-green-100">
                        <i class="fas fa-bell text-4xl text-slate-400"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900">Belum ada notifikasi</h3>
                    <p class="mt-3 text-slate-500">Kami akan memberitahu Anda tentang pesanan dan promosi menarik.</p>
                </div>

                <!-- Notifications List -->
                <div v-else class="space-y-3">
                    <Link
                        v-for="(notification, idx) in sortedNotifications"
                        :key="notification.id"
                        :href="notification.type === 'chat' ? route('chat.index') : route('notifications.index')"
                        data-aos="fade-up"
                        :data-aos-delay="idx * 30"
                        class="group block overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 transition duration-300 hover:shadow-lg hover:shadow-green-900/10 sm:rounded-[1.5rem]"
                        :class="!notification.is_read ? 'border-l-4 border-[#0c7c43] bg-gradient-to-r from-[#edf9ee] via-white to-white' : ''"
                    >
                        <div class="p-4 sm:p-5">
                            <div class="flex items-start gap-3 sm:gap-4">
                                <!-- Icon -->
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full sm:h-12 sm:w-12" :class="!notification.is_read ? 'bg-[#0c7c43]/10' : 'bg-slate-100'">
                                    <i :class="[notification.type === 'chat' ? 'fas fa-comments' : 'fas fa-bell', !notification.is_read ? 'text-[#0c7c43]' : 'text-slate-400']"></i>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex min-w-0 items-start justify-between gap-2 sm:gap-3">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-black text-slate-900" :class="!notification.is_read ? 'text-base' : 'text-sm'">
                                                {{ notification.title }}
                                            </h3>
                                            <p class="mt-1 break-words text-sm leading-6 text-slate-600">
                                                {{ notification.message }}
                                            </p>

                                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                                <span class="text-xs font-bold text-slate-400">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ new Date(notification.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }) }}
                                                </span>

                                                <button
                                                    v-if="!notification.is_read"
                                                    @click.prevent="read(notification.id)"
                                                    class="text-xs font-bold text-[#0c7c43] transition hover:text-[#0b2617]"
                                                >
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Tandai Dibaca
                                                </button>
                                                <span v-else class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-0.5 text-xs font-bold text-green-700">
                                                    <i class="fas fa-check-circle"></i>Sudah Dibaca
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Unread Indicator -->
                                        <div v-if="!notification.is_read" class="flex h-3 w-3 shrink-0 rounded-full bg-[#0c7c43] shadow-lg shadow-green-600/40"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

            </div>
        </div>

    </AuthenticatedLayout>
</template>