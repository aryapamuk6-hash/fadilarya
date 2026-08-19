import axios from 'axios'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.axios = axios

window.axios.defaults.headers.common[
    'X-Requested-With'
] = 'XMLHttpRequest'

window.Pusher = Pusher

// Only initialize Echo if all required config is available
if (
    import.meta.env.VITE_REVERB_APP_KEY &&
    import.meta.env.VITE_REVERB_HOST &&
    import.meta.env.VITE_REVERB_PORT
) {
    window.Echo = new Echo({
        broadcaster: 'reverb',

        key: import.meta.env.VITE_REVERB_APP_KEY,

        wsHost: import.meta.env.VITE_REVERB_HOST,

        wsPort: Number(
            import.meta.env.VITE_REVERB_PORT
        ),

        wssPort: Number(
            import.meta.env.VITE_REVERB_PORT
        ),

        forceTLS: false,

        enabledTransports: [
            'ws',
            'wss',
        ],
    })
} else {
    console.warn('Echo/Reverb not configured. Broadcasting features will not work.')
    window.Echo = null
}