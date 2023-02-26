import "bootstrap";

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Set base url
window.axios.defaults.baseURL = import.meta.env.APP_URL;

// Set header accept application/json
window.axios.defaults.headers.common["Accept"] = "application/json";

// Set POST content type to application/x-www-form-urlencoded
window.axios.defaults.headers.post["Content-Type"] =
    "application/x-www-form-urlencoded";

// Set PATCH content type to application/x-www-form-urlencoded
window.axios.defaults.headers.patch["Content-Type"] =
    "application/x-www-form-urlencoded";

// Set Authorization if existing
window.axios.interceptors.request.use(
    function (config) {
        // Do something before request is sent
        const authStorage = localStorage.getItem("auth-info");
        if (authStorage) {
            const auth = JSON.parse(authStorage) ?? null;
            const authToken = auth?.token ? "Bearer " + auth.token : "";
            config.headers["Authorization"] = authToken;
        }

        return config;
    },
    function (error) {
        // Do something with request error
        return Promise.reject(error);
    }
);

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
