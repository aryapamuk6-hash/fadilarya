<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    conversations: Array,
})

const sortedConversations = computed(() => {
    return [...props.conversations].sort((a, b) => a.id - b.id)
})
</script>

<template>
    <Head title="Chat" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#0c7c43]">Inbox</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Percakapan</h2>
                </div>
                <div class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-black uppercase tracking-[0.2em] text-slate-500">
                    {{ conversations?.length ?? 0 }} Chat
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-5xl py-8">
            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-[0_18px_40px_rgba(15,23,42,0.04)] ring-1 ring-slate-100 md:p-6" data-aos="fade-up">
                <div v-if="conversations && conversations.length" class="space-y-3">
                    <Link
                        v-for="conversation in sortedConversations"
                        :key="conversation.id"
                        :href="route('chat.show', conversation.id)"
                        class="group block rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4 transition duration-300 hover:-translate-y-0.5 hover:border-slate-300 hover:bg-white hover:shadow-md"
                    >
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-500 ring-1 ring-slate-200 group-hover:bg-slate-200">
                                <i class="fas fa-comments"></i>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-black text-slate-900">
                                            {{ conversation.product ? conversation.product.name : 'Chat' }}
                                        </h3>
                                        <p class="mt-1 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">
                                            {{ conversation.seller?.name || 'Penjual' }}
                                        </p>
                                    </div>
                                    <span class="rounded-full bg-slate-200 px-2 py-1 text-[10px] font-black uppercase tracking-[0.12em] text-slate-600">
                                        Baru
                                    </span>
                                </div>

                                <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">
                                    {{ conversation.messages && conversation.messages.length ? conversation.messages[conversation.messages.length - 1].message : 'Belum ada pesan' }}
                                </p>
                            </div>
                        </div>
                    </Link>
                </div>

                <div v-else class="rounded-[1.5rem] border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-slate-500">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-2xl text-slate-500 ring-1 ring-slate-200">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-black text-slate-800">Belum ada percakapan</h3>
                    <p class="mt-2 text-sm">Mulai chat dengan penjual untuk produk yang Anda suka.</p>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>