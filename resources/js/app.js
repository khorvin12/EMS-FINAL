import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import AdminLayout from './Layouts/AdminLayout.vue';
import EmployeeLayout from './Layouts/EmployeeLayout.vue';
import HRLayout from './Layouts/HRLayout.vue';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    const page = pages[`./Pages/${name}.vue`]
    
    // Auto-apply layouts based on page path
    if (!page.default.layout) {
      if (name.startsWith('Admin/')) {
        page.default.layout = AdminLayout
      } else if (name.startsWith('Employee/')) {
        page.default.layout = EmployeeLayout
      } else if (name.startsWith('HR/')) 
        page.default.layout = HRLayout
      
    }
    
    return page
  },

  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
})