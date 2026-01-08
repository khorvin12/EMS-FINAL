import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp, Head, Link } from '@inertiajs/vue3'
import Layout from './Layouts/Layout.vue'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    const page = pages[`./Pages/${name}.vue`]

    // ✅ Apply layout ONLY to non-Auth pages
    if (!name.startsWith('Auth/')) {
      page.default.layout = Layout
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
