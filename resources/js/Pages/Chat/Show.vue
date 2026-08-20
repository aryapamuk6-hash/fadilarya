<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { nextTick, onBeforeUnmount, onMounted, ref, watch, computed } from 'vue'

const props = defineProps({
    conversation: Object,
})

const sortedMessages = computed(() => {
    return [...props.conversation.messages].sort((a, b) => a.id - b.id)
})

const page = usePage()
const message = ref('')
const chatBox = ref(null)
let pollingTimer = null

const scrollToBottom = () => {
    nextTick(() => {
        if (chatBox.value) {
            chatBox.value.scrollTop = chatBox.value.scrollHeight
        }
    })
}

const refreshConversation = () => {
    router.reload({
        only: ['conversation'],
        preserveState: true,
        preserveScroll: true,
    })
}

const sendMessage = () => {
    if (!message.value) return

    router.post(
        route('chat.send', props.conversation.id),
        { message: message.value },
        {
            preserveScroll: true,
            onSuccess: () => {
                message.value = ''
                scrollToBottom()
            },
        }
    )
}

onMounted(() => {
    scrollToBottom()
    pollingTimer = window.setInterval(refreshConversation, 3000)
})

onBeforeUnmount(() => {
    if (pollingTimer) {
        window.clearInterval(pollingTimer)
    }
})

watch(
    () => props.conversation?.messages,
    () => scrollToBottom(),
    { deep: true }
)
</script>

<template>
    <Head title="Chat" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#0c7c43]">Inbox</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Chat</h2>
                </div>
            </div>
        </template>

        <div class="mx-auto max-w-5xl py-8">
            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm ring-1 ring-slate-100" data-aos="fade-up">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.22em] text-[#0c7c43]">Percakapan</p>
                        <h3 class="mt-2 text-2xl font-black text-slate-900">{{ conversation?.product ? conversation.product.name : 'Chat' }}</h3>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#edf9ee] text-[#0c7c43]">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                </div>

                <div ref="chatBox" class="h-[500px] overflow-y-auto rounded-[1.5rem] border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4">
                    <div v-for="msg in sortedMessages" :key="msg.id" class="mb-4 flex flex-col">
                        <div class="mb-1 text-xs font-bold uppercase tracking-[0.12em] text-slate-400" :class="msg.sender?.id === page.props.auth?.user?.id ? 'self-end' : 'self-start'">
                            {{ msg.sender?.name }}
                        </div>
                        <div
                            :class="msg.sender?.id === page.props.auth?.user?.id ? 'ml-auto bg-[#0c7c43] text-white' : 'mr-auto bg-white text-slate-700 ring-1 ring-slate-200'"
                            class="max-w-[80%] rounded-2xl px-4 py-3 text-sm leading-6 shadow-sm"
                        >
                            {{ msg.message }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex gap-3">
                    <input
                        v-model="message"
                        type="text"
                        placeholder="Tulis pesan..."
                        class="flex-1 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[#0c7c43] focus:bg-white focus:ring-4 focus:ring-green-100"
                        @keyup.enter="sendMessage"
                    >

                    <button @click="sendMessage" class="rounded-2xl bg-[#0c7c43] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-green-900/15 transition hover:-translate-y-0.5 hover:bg-[#0b2617]">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim
                    </button>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>