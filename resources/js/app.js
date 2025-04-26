import {createApp, h} from 'vue';
import {createInertiaApp, Head, Link} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import axios from 'axios'

axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://localhost:8000'
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'TO-DO-APP';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .component("Head", Head)
            .component("Link", Link)
            .mount(el);
    },
    progress: {
        color: '#007F3E',
        showSpinner: true,
        zIndex:'999'
    },
});
