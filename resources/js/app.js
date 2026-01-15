import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue'
import { createInertiaApp, Head, Link } from '@inertiajs/vue3'

import AdminLayout from './Layouts/AdminLayout.vue'

createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    const page = pages[`./Pages/${name}.vue`]

    if (name.startsWith('Auth/')) {
      page.default.layout = null
    } else if (name.startsWith('Admin/')) {
      page.default.layout = AdminLayout
    } else if (name.startsWith('Employee/')) {
      page.default.layout = null
    } else if (name.startsWith('HR/')) {
      page.default.layout = null
    }

    return page
  },

  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .component('Head', Head)
      .component('Link', Link)
      .mount(el)
  },
})