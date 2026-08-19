import '../css/app.css'
import './bootstrap'

import { createInertiaApp, router } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'

import AOS from 'aos'
import 'aos/dist/aos.css'

const appName = import.meta.env.VITE_APP_NAME || 'Lumira'

const normalizeImageUrl = (image) => {
    if (!image) return ''
    if (image.startsWith('http://') || image.startsWith('https://')) return image
    if (image.startsWith('/storage/')) return image
    if (image.startsWith('storage/')) return `/${image}`
    return `/storage/${image}`
}

AOS.init({
    duration: 800,
    easing: 'ease-out-cubic',
    once: false,
    offset: 40,
    mirror: true,
    disableMutationObserver: false,
    anchorPlacement: 'top-bottom',
})

const refreshAos = () => {
    requestAnimationFrame(() => {
        AOS.refreshHard()
    })
}

router.on('finish', refreshAos)
window.addEventListener('scroll', refreshAos, { passive: true })
window.addEventListener('resize', refreshAos)

createInertiaApp({
    title: (title) => `${title ? `${title} - ` : ''}${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el)
    },
    progress: {
        color: '#166534',
    },
})